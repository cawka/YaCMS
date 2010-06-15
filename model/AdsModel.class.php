<?php

class AdsModel extends TableAutoColumnModel
{
	var $myDataTable=array( "cat_id","reg_id","phone","publ_begin" );
	var $mySpecialTable=array( );
	
	var $myCatId;
	var $myData;
	var $mySingleProduct;
	var $myCurRowId;
	var $myUUID;

	var $myBriefCols;
	var $myTopBriefCols;

	public $AnnEditType;
	public $myInfo;

	public $myCatalog;

	function __construct( $php,$cat_id=NULL )
	{
		global $DB,$SETTINGS;

		if( !isset($cat_id) )
		{
			if( isset($_REQUEST['uuid']) )
			{
				$_REQUEST['id']=AdsHelper::long_id2id( $_REQUEST['uuid'] );
			}
			
			//if( isAdmin() ) $DB->debug=true;
			
			if( isset($_REQUEST['id']) ) //discover correct `cat_id`. We are going to pay price for this...
			{
//				if( !(1<=$_REQUEST['id'] &&  $_REQUEST['id']<=4294967296) ) ErrorHelper::redirect( "/no_advertisment.html" );
				$row=APC_GetRow(
					array( "a",$_REQUEST['id'] ), $DB,
					 "SELECT cat_id FROM data ".
						"WHERE flag_category IS NULL AND ".
						"id=".$DB->qstr($_REQUEST['id']), 86400 );
				$_REQUEST['cat_id']=$row['cat_id'];

//				if( isAdmin() )
//				{
//					print_r( $row );
//				}

//				if( !$_REQUEST['cat_id'] ) ErrorHelper::redirect( "/no_advertisment.html" );
			}
			$this->myCatId=$_REQUEST['cat_id'];
		}
		else
			$this->myCatId=$cat_id;

		$this->myInfo=CatalogsHelper::getInfo( $this->myCatId );
		if( sizeof($this->myInfo)==0 ) ErrorHelper::redirect( "/" );

		$this->myParentPath=CatalogsHelper::getParentPath( $this->myCatId, $this->myInfo['cat_tree'] );

		parent::__construct( $this->myInfo['cat_type'],$php,"ann_".$this->myInfo['type_data'],
			array( ),"id",true );

		// This f%@%ing caching caused ^#%# unpredictable problem with unpredictable ad categories
		$this->myColumns['cat_id']->myValue=$this->myCatId;
		//

		$this->myElementsPerPage=$SETTINGS['advertisement_count'];
		if( isset($_REQUEST["rss"]) ) $this->myElementsPerPage=20;

		$this->addSortColumns( array("publ_begin"=>"publ_begin") );
		$this->getColumnOrders( );

		$this->myFilterRegionsColumn=new RegionsColumn( "reg_id", $langdata['cat_reg_id'],
			$langdata['cat_reg_id_msg'],3,true );
		$this->myFilterRegionsColumn->blankLevelZero=false;
		$this->myFilterRegionsColumn->myClass="_sortingselect";

/*		if( isAdmin() ) 
		{
			print_r( $this->myColumns['cat_id'] );
			die;
			;
		}*/
	}

	private function setCatalog( )
	{
		$parent=$this->myParentPath[ sizeof($this->myParentPath)-2 ];
		if( $parent['cat_template']=='realestate.tpl' ||
			$parent['cat_template']=='realestate_nomap.tpl' )
		{
			$this->myCatalog=new CatalogsModel( "catalogs" );
			$this->myCatalog->myHelper=$this->myHelper;
			$this->myCatalog->setCatId( $parent['cat_id'], false/*do not redirect*/ );

			$this->myInfo['cat_name']=$this->myCatalog->myInfo['cat_name']." - ".$this->myInfo['cat_name'];

			$req=array();
			$this->myCatalog->collectData( $req );
		}
	}

	protected function getStaticColumns( )
	{
		global $LANG,$langdata;

		$cols=array(
			'reg_id'=>	new RegionsColumn("reg_id",$langdata['cat_reg_id'],
							$langdata['cat_reg_id_msg'],-1,true ),
		);
		$cols['reg_id']->mySpecType="region";
		$cols['reg_id']->myBriefMsg=$langdata['cat_reg_id'];
		
		return $cols;
	}

	protected function getAllColumns( )
	{
		global $langdata;

		parent::getAllColumns( );

		$this->addColumns( array(
				'cat_id'=>	new HiddenExactValueColumn( "cat_id",$this->myCatId ),
				'phone'=>	new PhoneColumn("phone",$langdata['cat_phone_num'],
								$langdata['cat_phone_num_msg'],$langdata['cat_phone_num_brief'],
								false,$langdata['tooltip_phone_code'],$langdata['tooltip_phone_num']),
				'publ_begin'=>new PublicationTimeColumn("publ",$langdata['cat_publ']),
		) );
	}

	function changeLanguage( $lang,$cat_id )
	{
		global $LANG,$langdata;
		$LANG=$lang;
		$langdata=null;
		setGlobalLang( );

		$this->myColumns['phone']->myBriefMsg=$langdata['cat_phone_num_brief'];
		$this->myColumns['reg_id']->myBriefMsg=$langdata['cat_reg_id'];

		$this->discoverColumns( );
	}

	protected function processDynColumnBefore( &$data )
	{
		if( $data['attr_name']=='reg_id' )
		{
			$this->myStaticColumns['reg_id']->myIsBriefInTop=$data['is_brief_in_top']=='t';

			if( is_numeric($data['attr_options']) )
			{
				$this->myStaticColumns['reg_id']->myLevels=$data['attr_options'];
			}
			if( $data['attr_options2']=='hidden' )
			{
				$this->myStaticColumns['reg_id']=new HiddenColumn( "reg_id","12");
				$this->myStaticColumns['reg_id']->myIsProtected=true;
			}
			return false;
		}

		$this->mySpecialTable[$data['attr_name']]=$data['attr_name'];
		return true;
	}

	protected function restoreCache( $id )
	{
		global $theAPC;
		$this->mySpecialTable=$theAPC->fetch( "announcement-specialtable|$id" );
	}

	protected function cacheData( $id )
	{
		global $theAPC;
		$theAPC->cache( "announcement-specialtable|$id", $this->mySpecialTable, 0 );
	}

	protected function processDynColumnAfter( &$col, &$data )
	{
		if( $data['attr_name']=='reg_id' )
		{
			return false;
		}

		return true;
	}

	function getColumnOrders( )
	{
		global $LANG;
		if( !isset($this->myType) ) return;

		$this->myBriefCols=APC_GetAssoc( array("attr_lang","brief",$LANG,"type_id",$this->myType),$this->myDB,
			"SELECT attr_name,attr_name FROM attrs_lang$LANG
				WHERE type_id='$this->myType' AND attr_group>0 ORDER BY attr_brief_order,attr_group",
			0
		 );

		$this->myTopBriefCols=APC_GetAssoc( array("attr_lang","topbrief",$LANG,"type_id",$this->myType),$this->myDB,
			"SELECT attr_name,attr_name FROM attrs_lang$LANG
				WHERE type_id='$this->myType' AND attr_top_order>0 ORDER BY attr_top_order",
			0
		);

		$this->myExtraBriefCols=APC_GetAssoc( array("attr_lang","extrabrief",$LANG,"type_id",$this->myType),$this->myDB,
		"SELECT attr_name,attr_name FROM attrs_lang$LANG
			WHERE type_id='$this->myType' AND
				  (attr_group=0 OR attr_group IS NULL) AND
				  (show_in_text='f' OR show_in_text IS NULL) AND
				  attr_type NOT IN (7,17) ORDER BY attr_order,attr_group",
			0
		);
	//	if( isAdmin() )
	//	{
	//		print "attr_lang,post,$LANG,type_id,$this->myType";
	//	}

		$this->myPostCols=APC_GetAssoc( array("attr_lang","post",$LANG,"type_id",$this->myType),$this->myDB,
			"SELECT attr_name,attr_name FROM attrs_lang$LANG
				WHERE type_id='$this->myType'
				ORDER BY attr_post_order,attr_group",
			0
		);

		$reg_ok=false;
		foreach( $this->myPostCols as &$name )
		{
			if( $name=="reg_id" ) $reg_ok=true;
		}
		$this->myPostCols=array_merge( $this->myPostCols, array('cat_id'=>'cat_id','phone'=>'phone','publ_begin'=>'publ_begin') );
		if( !$reg_ok ) $this->myPostCols=array_merge( $this->myPostCols, array("reg_id"=>"reg_id") );

	}

	public function validateSave( &$request )
	{
		$error="";
		$err_cols=array();
		foreach( $this->myColumns as $col )
		{
			if( $col->myGenType=="separator" ) continue;
			$ret=$col->checkBeforeSave( $request );
			if( !$ret )
			{
				$error.=$col->myRequired;
				$err_cols[$col->myName]=true;
			}
		}
		if( sizeof($err_cols)!=0 || $error!="" )
		{
			$request['error']=$error;//."preved";
			$request['err_cols']=$err_cols;
		}

		return $error;
	}

	public function save_edit( &$request )
	{
		$id=$request[$this->myId];
		return $this->saveRowUpdate( $id,$request );
	}

	function saveRowUpdate( $id,&$request )
	{
		global $_SERVER,$langdata,$LANG;

		$this->AnnEditType="update";
		$this->myCurRowId=$id;
		$this->myUUID=$request["uuid"];


		$row=$this->myDB->GetRow( "SELECT publ_begin,notify_sent FROM data WHERE id=".$this->myDB->qstr($id) );
		$request['publ_begin']=$row['publ_begin'];
		if( $row['notify_sent']=='t' ) $this->AnnEditType="prolongation";

		$this->myDB->Execute( "BEGIN" );

		//////////////////////////////////
		// STAGE 1
		$ret="UPDATE data SET notify_sent='f', ";

		$i=0;
		foreach( $this->myDataTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			if( $this->myColumns[$field]->myIsReadonly ) continue;
			if( !$this->myColumns[$field]->mySQL )
			{
				$this->myColumns[$field]->getUpdate( $request );
				continue;
			}

			if( $i!=0 ) $ret.=","; $i=1;
			$ret.=$this->myColumns[$field]->getUpdate( $request );
		}
		$ret.=",publ_modif=NOW(),from_ip='".$_SERVER["REMOTE_ADDR"]."'::inet ";
		$ret.=" WHERE id='$id' ";

		$this->myDB->Execute( $ret );

		//////////////////////////////////
		// STAGE 2
		$ret="UPDATE $this->myTableName SET ";
		$i=0;
		foreach( $this->mySpecialTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			if( $this->myColumns[$field]->myIsReadonly ) continue;
			if( !$this->myColumns[$field]->mySQL )
			{
				$this->myColumns[$field]->getUpdate( $request );
				continue;
			}
			if( $i!=0 ) $ret.=","; $i=1;
			$ret.=$this->myColumns[$field]->getUpdate( $request);
		}

		$ret.=" WHERE id='$id' ";

		$rr=$this->myDB->Execute( $ret );
		$this->myDB->Execute( "COMMIT" );
	}

	function saveRowInsert( &$request )
	{
		global $_SERVER,$langdata,$LANG;
//		if( isAdmin() ) $this->myDB->debug=true;

		if( isset($request['error']) || isset($request['err_cols']) )
		{
			$this->myParent->showNewAnnoncement( $request );
			exit( 0 );
		}

		if( !$this->myHelper->autorizeAd($request) )
		{
			$this->myParent->showNewAnnoncement( $request );
			exit( 0 );
		}

		$this->myDB->Execute( "BEGIN" );
		$id=$this->myDB->GenID( "data_id_seq" );

		// Hack for new images engine
		$request['id']=$id;
		$request['publ_begin']=date( "Y-m-d H:i:s" );
		// end of hack

		$this->AnnEditType="new";

		$this->myCurRowId=$id;
		$this->myUUID=AdsHelper::generateUUID( );

		//////////////////////////////////
		// STAGE 1
		$ret="INSERT INTO data (id";
		if( $this->requirePayment ) $ret.=",flag_category";
		foreach( $this->myDataTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			$ret.=",".$this->myColumns[$field]->getUpdateName();
		}
		$ret.=",long_id,from_ip";
		if( isUserLogged() ) $ret.=",user_id";
		$ret.=")";

		$ret.=" VALUES('$id'";
		if( $this->requirePayment ) $ret.=",'1'";
		foreach( $this->myDataTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			$ret.=",".$this->myColumns[$field]->getInsert( $request );
		}
		$ret.=",'$this->myUUID','".$_SERVER["REMOTE_ADDR"]."'::inet";
		$this->myData['from_ip']=$_SERVER["REMOTE_ADDR"];

		if( isUserLogged() ) $ret.=",'".$_SESSION['user']."'";
		$ret.=")";
		$this->myDB->Execute( $ret );

		//////////////////////////////////
		// STAGE 2
		$ret="INSERT INTO $this->myTableName (id";
		foreach( $this->mySpecialTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			$ret.=",".$this->myColumns[$field]->getUpdateName();
		}
		$ret.=")";

		$ret.=" VALUES('$id'";
		foreach( $this->mySpecialTable as $field )
		{
			if( $this->myColumns[$field]->myGenType=="separator" ) continue;
			$ret.=",".$this->myColumns[$field]->getInsert( $request );
		}
		$ret.=")";

		$rr=$this->myDB->Execute( $ret );

		if( isUserLogged() )
		{
			$this->myDB->Execute( "UPDATE users
									SET
										u_adv_count=u_adv_count+1,
										u_adv_count_cur=u_adv_count_cur+1
									WHERE user_id='$_SESSION[user]'" );
		}

		$this->myDB->Execute( "COMMIT" );
	}

	public function save_stage3( &$request, $send_email=true )
	{
		$this->myDB->Execute( "BEGIN" );
		$this->getRowToEdit( $request,true /*including hidden*/ );

		$isphoto=$this->isImage( $this->myData,true )?'TRUE':'FALSE';
		$photo=$isphoto=='TRUE'?$this->getFirstImagePreview( $this->myData,true ):"";

		$srch=$this->myDB->qstr(addslashes( CatalogsHelper::printPath($this->myData['cat_id']," ")." ".
						  $this->showBrief($this->myData,true,true) ));
		$email=$this->myDB->qstr(addslashes( $this->myData['email'] ));
		$real_email=$this->myData['email'];

		$this->myDB->Execute(
"UPDATE data
	SET
		brief_is_photo='$isphoto',
		search        =$srch,
		brief_photo   ='$photo',
		email_copy    =$email
	WHERE id='$this->myCurRowId'" );

		$this->updateDenormalizedData( $this->myCurRowId,$this->myData );
		$this->myDB->Execute( "COMMIT" );

		if( $send_email )
		{
//			$this->sendAdvertismentToEmail( $SETTINGS['notice_email'],$this->myData,"admin_email" );
			$this->sendAdvertismentToEmail( $real_email,$this->myData );
		}

//		if( isAdmin() ) die;
	}

	function updateDenormalizedData( $id,&$data )
	{
		global $LANG;
		$br=array();
		$cat=array();
		$tops=array();

		$this->myData=&$data;

		$orig_lang=$LANG;

		$this->myPostCols['publ_begin']=NULL;
		for( $i=1; $i<=3; $i++ )
		{
			$this->changeLanguage( $i,$data['cat_id'] );
			$br[$i]="brief$i=".$this->myDB->qstr( $this->showBrief($data,true,false) );
			$cat[$i]="cat_brief$i=".$this->myDB->qstr( CatalogsHelper::printPath($data['cat_id'],">>") );
			$tops[$i]="brief_top$i=".$this->myDB->qstr( $this->showTopBrief( $data ) );

			$tmpl=new ReklamaUA( );
			$tmpl->assign( "this", $this );
			$tmpl->assign( "item", $this->myData );

			$tmpl->caching=false;
			$full[$i]="full$i=".$this->myDB->qstr( stripslashes($tmpl->fetch("ads/single_formatdata.tpl","($_SESSION[group])($data[id])()")) );
			$tmpl=NULL;
		}
		$LANG=$orig_lang;

//		$DB->debug=true;
		$this->myDB->Execute( "UPDATE data SET ".implode(",",$br).",".
												 implode(",",$cat).",".
												 implode(",",$tops).",".
												 implode(",",$full).
											     " WHERE id='$id'" );
		$this->changeLanguage( $LANG,$data['cat_id'] );
//		$DB->debug=false;
	}

	function sendAdvertismentToEmail( $email,&$request,$tmpl_register="" )
	{
		global $LANG,$SETTINGS;
		if( !$email || $email=="" ) return;

		$to=$email;

		switch( $this->AnnEditType )
		{
			case "new":
				$subject=   $SETTINGS['notify_reply_subject'];//"Новое объявление";
				break;
			case "update":
				$subject=   $SETTINGS['notify_reply_subject_edit'];
				break;
			case "prolongation":
				$subject=   $SETTINGS['notify_reply_subject_edit_prolong'];//"Новое объявление";
				break;
		}

		$tmpl=new ReklamaUA( );
		$tmpl->caching=false;

		if( $tmpl_register!="" ) $tmpl->assign( "$tmpl_register", $tmpl_register );

		$tmpl->assign( "uuid", $this->myUUID );
		$tmpl->assign( "this", $this );

		Mail::sendFromRobot( $to,$subject,
			$tmpl->fetch("ads/email.tpl"),
			$tmpl->fetch("ads/email.txt.tpl")
		);
	}

	function deleteRow( &$request,$robot=false )
	{
		global $LANG,$DB;
		if( isset($request["uuid"]) )
		{
			$row=$this->myDB->GetRow( "SELECT * FROM data d WHERE long_id='".$request['uuid']."'" );
		}

		if( $row )
		{
			if( isset($request['admin']) &&
			    $request['confirm_delete']!='1' &&
				($row['comm_bold']=='t' ||
				 $row['comm_up']  =='t' ||
				 $row['comm_top'] =='t') )
			{
				$str=array();
				if( $row['comm_bold']=='t' ) array_push( $str,"Выделение" );
				if( $row['comm_up']=='t' )   array_push( $str,"Наверху списка" );
				if( $row['comm_top']=='t' )  array_push( $str,"Рекламный блок" );

				return "admin";
			}

			if( $row['user_id']!="" )
			{
				if( !$robot ) $dec_count=" u_adv_count=u_adv_count-1, ";
				$this->myDB->Execute( "UPDATE users SET $dec_count u_adv_count_cur=u_adv_count_cur-1 WHERE user_id='$row[user_id]'" );
			}
			$this->myDB->Execute( "DELETE FROM data WHERE id='".$row['id']."'" );
		}
		else
			return "user";

		return NULL;
	}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
	public function encodeComm( &$array )
	{
		$ret="";
		foreach( $array as $key=>$value )
		{
			if( $ret!="" ) $ret.="&";
			$ret.="comm[$key]=$value";
		}
		return "$ret";

	}


	function postSave( &$request )
	{
		global $SETTINGS;

		if( $this->requirePayment )
		{
				header( "Location: /submit_payed.html?adv=$this->myCurRowId&uuid=$this->myUUID&type=new" );
		}
		else
		{
			if( $this->AnnEditType=="new" )
				header( "Location: /submit.html?adv=$this->myCurRowId&uuid=$this->myUUID&type=new" );
			else
				header( "Location: /show-$this->myCurRowId.html?uuid=$this->myUUID&type=$this->AnnEditType" );
		}
	}

	function postDelete( &$request )
	{
		//header( "Location: index.php" );
	}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

	public function getRowToEdit( &$request, $show_hidden=false )
	{
		if( !isset($request['id']) ) ErrorHelper::redirect( );
		$this->mySingleProduct=$request['id'];
		$this->myCurRowId=$request['id'];

		$where=array();
		$where[]="d.id=".$this->myDB->qstr($request['id']);
		if( !$show_hidden ) $where[]="flag_category IS NULL";

		$this->myTableName=" $this->myTableName t JOIN (SELECT * FROM data d LEFT JOIN users u ON d.user_id=u.user_id) d ON d.id=t.id ";
		$this->myData=$this->myDB->GetRow( "SELECT * FROM $this->myTableName WHERE ".implode(" AND ",$where) );
	}

	public function getRowToShow( &$request )
	{
		global $langdata;
		
		$ret=$this->getRowToEdit( $request );

		// For advertisment from registered users we have additional information
		$this->addColumns( array(
				"u_phone2"=>new PhoneColumn( "u_phone2",$langdata['cat_phone_num'],$langdata['cat_phone_num_msg'],$langdata['cat_phone_num_brief'],false,$langdata['tooltip_phone_code'],$langdata['tooltip_phone_num']),
				"u_phone3"=>new PhoneColumn( "u_phone3",$langdata['cat_phone_num'],$langdata['cat_phone_num_msg'],$langdata['cat_phone_num_brief'],false,$langdata['tooltip_phone_code'],$langdata['tooltip_phone_num']),
				"u_phone4"=>new PhoneColumn( "u_phone4",$langdata['cat_phone_num'],$langdata['cat_phone_num_msg'],$langdata['cat_phone_num_brief'],false,$langdata['tooltip_phone_code'],$langdata['tooltip_phone_num']),
			) );
		$this->myPostCols=array_merge( $this->myPostCols, array(
				"u_phone2","u_phone3","u_phone4",
			) );

		if( !isAdmin() && !(isUserLogged() && $this->myData['user_id']==$_SESSION['user'] ) )
		{
			$this->myDB->Execute( "UPDATE data SET show_count=show_count+1 where id=".$this->myDB->qstr($request['id']) );
		}
	}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

	function showBrief( &$row,$rebuild=false,$search=false )
	{
		global $LANG;
		$ret="";

		if( $rebuild )
		{
			$prevgroup=-1;
			$current="";

			foreach( $this->myColumns as &$col )
			{
				if( $col->myIsBrief>0 &&!isset($this->myPictureColumns[$col->myName]) )
				{
					if( $col->myIsBrief!=$prevgroup )
					{
						if( $current!="" && !$search ) $current.="</div>";
						$ret.=$current;
						$current="";
					}

					$vvv=(!$search)?$col->myBriefMsg.($col->myBriefMsg!=""?":&nbsp;":""):"";
					$vvv.=$col->extractPreviewValue( $row );
					if( $current=="" && !$search )
						$current="<div class=\"smallannatributes\">";
					else if( $vvv!="" && !$search )
						$current.="&nbsp;| ";
					else if( $search ) $current.=" ";

					$current.=$vvv;
					$prevgroup=$col->myIsBrief;
				}
			}
			if( $current!="" && !$search ) $current.="</div>";
			$ret.=$current;
		}
		else
			$ret=$row["brief$LANG"];
		return $ret;
	}

	function showTopBrief( &$row )
	{
		global $LANG;
		$ret="";
		foreach( $this->myTopBriefCols as $colname )
		{
			$col=&$this->myColumns[$colname];
			if( $col->myGenType=="photo" ) continue;

			if( $col->mySpecType=="adtext" )
				$val=preg_replace( array("/\n/"),array(" "), $col->extractOnlyValue( $row ) );
			else if( $col->mySpecType=="region" )
				$val=$col->extractOnlyValue( $row,3 );
			else
				$val=$col->extractPreviewValue( $row );

			if( $val=="" ) continue;

			if( $col->mySpecType!="adtext" && $col->myIsBriefInTop )
				$ret.=$col->myBriefMsg.($col->myBriefMsg!=""?": ":"");

			$ret.=strip_tags( preg_replace(array("/&nbsp;/","/<<<\/div>/"),array(" ","\n"),$val) )."\n";
		}
		return $ret;
	}

	function getExtraBrief( &$row )
	{
		$ret="";

		foreach( $this->myExtraBriefCols as $colkey )
		{
			if( $this->myColumns[$colkey]->myGenType=="separator" ) continue;
			$add=chop( $this->myColumns[$colkey]->extractPreviewValue( $row ) );
			if( $add=="" ) continue;

			if( $this->myColumns[$colkey]->myBriefMsg!="" )
				$add=$this->myColumns[$colkey]->myBriefMsg.":&nbsp;".$add;

			if( $ret!="" ) $ret.=", ";
			$ret.=$add;
		}

		return $ret;
	}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

	public function collectData( &$request )
	{
		$this->setCatalog( );

		// deduce ORDER BY clause
		$sort_temp=$request['sort'];
		$sort=NULL;
		$data_sort=" ORDER BY comm_up DESC,publ_begin DESC";
		$ann_sort="";

		foreach( $this->mySortColumns as $k ) { if( $k==$sort_temp ) { $sort=$k; break;} }
		if( isset($sort) && $sort!="" )
		{
			$sortColumn=$this->myColumns[$sort];
			$data_sort=" ORDER BY comm_up DESC";
			$ann_sort=$sortColumn->getSortName();
			if( isset($_REQUEST['desc']) ) $ann_sort.=" DESC";
		}

		//  deduce WHERE clause
		$where=array();
		$where[]="cat_id=".$this->myDB->qstr($this->myCatId);
		
		if( isset($request["withphoto"]) ) $where[]="(brief_photo IS NOT NULL AND brief_photo!='')";
		if( isset($request["reg_id"])    )
		{
			$region=preg_replace( "/\D/", "", $request["reg_id"] );
			if( $region!="" and strlen($region)<6 ) $where[]="d.reg_tree~'*.$region.*' ";
		}
		$where[]="flag_category IS NULL";

		if( sizeof($where)>0 ) $where=" WHERE ".implode( " AND ",$where );

//		if( isAdmin() ) {
//			$this->myDB->debug=true;
//		}
		$res=$this->collectDataBaseRaw1( $request, "*", $where, $data_sort, $ann_sort );
		$this->myData=$res;//->GetRows( );
	}

    protected function collectDataBaseRaw1( &$request, $select, $where, $data_sort, $ann_sort )
    {
        $count=$this->myDB->GetOne( "SELECT count(*) FROM ".
										"(SELECT id FROM data d $where LIMIT $this->myMaxElementCount) c" );
        $offset=$this->getCurPage( $request,$count );

		if( $ann_sort=="" )
		{
	        $res=$this->myDB->Execute( "SELECT * FROM ".
"(SELECT * FROM data d $where $data_sort LIMIT ".$this->myElementsPerPage." OFFSET ".($offset*$this->myElementsPerPage).") d ".
"JOIN $this->myTableName t ON d.id=t.id ".
"LEFT JOIN users u ON d.user_id=u.user_id $data_sort" );
		}
		else
		{
	        $res=$this->myDB->Execute( "SELECT * FROM ".
"(SELECT * FROM data d $where) d ".
"JOIN $this->myTableName t ON d.id=t.id ".
"LEFT JOIN users u ON d.user_id=u.user_id $data_sort,$ann_sort ".
"LIMIT ".$this->myElementsPerPage." OFFSET ".($offset*$this->myElementsPerPage) );
		}

        return $res;
    }

	// compatibility stuff

    function resetCounter( )
    {
        $this->myParentCounter=0;
    }

    function getNextParentPath( )
    {
        if( !isset($this->myParentCounter) ) return NULL;//$this->myParentCounter=0;
        if( sizeof($this->myParentPath)<=$this->myParentCounter  ) return NULL;

        return $this->myParentPath[$this->myParentCounter++];
    }

    function insertDivs( )
    {
        $ret="";
        for( $i=0; $i<$this->myParentCounter; $i++ ) $ret.="</div>";
        return $ret;
    }

	function getSubCatalogs( $cat_id, $selected=NULL )
	{
		static $catalog;
		if( !isset($catalog) ) $catalog=new CatalogColumn( "cat_id", "", NULL, -1, false );

//		$fake_request=array( "cat_cat_id"=>$cat_id, "cat_id"=>$selected );

		$catalog->myWhere["cat_cat_id"]=$cat_id;

		$catalog->getOptions( );
		return $catalog->myOptions;
	}
}
