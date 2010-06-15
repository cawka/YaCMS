<?php

class TableAutoColumnModel extends TableModel
{
	protected $myAttrTable="attrs";
	public    $myType;
	protected $myIgnoreAttrType=array();
	public 	  $myPictureColumns=array();

	protected $myStaticColumns;
	protected $myDynamicColumns;

	public function __construct( $type,$php,$tablename,$columns=array(),$id="id",$offsets=false )
	{
		global $DB;
		parent::__construct( $DB,$php,$tablename,$columns,$id,$offsets );

		$this->myType=$type;
		$this->discoverColumns( );
	}

	protected function discoverColumns( )
	{
		global $LANG,$theAPC;

		$tmp=&$theAPC->fetch( "autocolumntable|$LANG|$this->myAttrTable|$this->myType" );
		if( !$tmp )
		{
			$this->myColumns=array();
			$this->getAllColumns( );

			$this->cacheData( "$this->myAttrTable|$this->myType" );

			$theAPC->cache( "autocolumntable|$LANG|$this->myAttrTable|$this->myType",
						    $this->myColumns,0 );
			$theAPC->cache( "autocolumntable-photos|$LANG|$this->myAttrTable|$this->myType",
							$this->myPictureColumns,0);
			$theAPC->cache( "autocolumntable-sort|$this->myAttrTable|$this->myType",
						    $this->mySortColumns,0 );
		}
		else
		{
			$this->restoreCache( "$this->myAttrTable|$this->myType" );
			
			$this->myColumns=&$tmp;
			$this->myPictureColumns=&$theAPC->fetch( "autocolumntable-photos|$LANG|$this->myAttrTable|$this->myType" );
			$this->mySortColumns=&$theAPC->fetch( "autocolumntable-sort|$this->myAttrTable|$this->myType" );
		}
		
		$this->setColumnCommonData( );
	}

	protected function getAllColumns( )
	{
		$this->myStaticColumns =&$this->getStaticColumns( );
		if( !$this->myStaticColumns ) $this->myStaticColumns=array( );

		$this->myDynamicColumns=&$this->getDynamicColumns( );
		if( !$this->myDynamicColumns ) $this->myDynamicColumns=array( );

		$this->myColumns=
			array_merge(
				$this->myColumns,
			array_merge(
				$this->myStaticColumns,
				$this->myDynamicColumns
			) );
//		unset( $this->myStaticColumns );
//		unset( $this->myDynamicColumns );
	}

	protected function getDynamicColumns( )
	{
		global $LANG,$langdata;
//		$this->myDB->debug=true;
		if( !isset($this->myType) ) return array();

		$cols=array();
		$sorts=array();

		$attr_data=APC_GetRows( array("attr_lang","full",$LANG,"type_id",$this->myType),$this->myDB,
			"SELECT a.* FROM attrs_lang$LANG a
				WHERE type_id='$this->myType'
				ORDER BY attr_order,attr_group",
			0
		);
//		print "Process auto columns [$this->myType]<br/>";
        foreach( $attr_data AS &$data )
		{
			if( !$this->processDynColumnBefore($data) ) continue;

			$err=false;
			switch( $data['attr_type'] )
			{
				case 1:
					$cols[$data['attr_name']]=
						new TextColumn( $data['attr_name'],        $data['attr_descr'],
										 $this->getReq($data),      $data['attr_group'],
										 $data['attr_brief_msg'],"",$data['attr_options_msg'],
										 false,$data['attr_options'] );
					if( is_numeric($data['attr_options2']) )
						$cols[$data['attr_name']]->myLimit=$data['attr_options2'];
					break;
				case 2:
					$cols[$data['attr_name']]=
						new IntegerColumn( $data['attr_name'],   $data['attr_descr'],
											$this->getReq($data), $data['attr_group'],
											$data['attr_brief_msg'],NULL, 0,"_int",
											$data['attr_options_msg'],$data['attr_options'] );
					break;
				case 12:
					$cols[$data['attr_name']]=
						new DoubleColumn( $data['attr_name'],  $data['attr_descr'],
										   $this->getReq($data),$data['attr_group'],
										   $data['attr_brief_msg'],NULL,0,"_int",
										   $data['attr_options_msg'],$data['attr_options'] );
					break;
				case 3:
					$cols[$data['attr_name']]=
						new UserPhotoColumn( $data['attr_name'], $data['attr_descr'],
											  $data['attr_brief_msg'] );
					$this->myPictureColumns[$data['attr_name']]=$data['attr_name'];
					break;
				case 4:
					$count=(isset($data['attr_options2'])&&is_numeric($data['attr_options2']))?$data['attr_options2']:10;
					$cols[$data['attr_name']]=
						new UserPhotoColumn( $data['attr_name'], $data['attr_descr'],
										      $data['attr_brief_msg'],$count );
					$this->myPictureColumns[$data['attr_name']]=$data['attr_name'];
					break;
				case 20:
					$count=(isset($data['attr_options2'])&&is_numeric($data['attr_options2']))?$data['attr_options2']:1;
					$cols[$data['attr_name']]=
						new UserLogoColumn( $data['attr_name'], $data['attr_descr'],
										      $data['attr_brief_msg'],$count );
					$this->myPictureColumns[$data['attr_name']]=$data['attr_name'];
					break;
				case 5:
					$cols[$data['attr_name']]=
						new PriceColumn( $data['attr_name'], $data['attr_descr'],
										  $this->getReq($data),$data['attr_group'],
										  $data['attr_brief_msg'],$data['attr_options_msg'],
										  $data['attr_options2'] );
					break;
				case 16:
					$arr_descr=preg_split( "/,/", $data['attr_options_msg'] );
					$tips=preg_split( "/,/", $data['attr_tooltip'],2 );
					$cols[$data['attr_name']]=
						new PriceExtraColumn( $data['attr_name'],$data['attr_descr'],$arr_descr,
											   $this->getReq($data), $data['attr_group'],
											   $data['attr_brief_msg'],$data['attr_options2'],
											   isset($tips[0])?$tips[0]:"",
											   isset($tips[1])?$tips[1]:"" );
					break;
				case 18:
					$tips=preg_split( "/,/",$data['attr_tooltip'],2 );
					$cols[$data['attr_name']]=
						new PriceTorgColumn( $data['attr_name'], $data['attr_descr'],
											  $this->getReq($data), $data['attr_group'],
											  $data['attr_brief_msg'],$data['attr_options2'],
											  isset($tips[0])?$tips[0]:"",
											  isset($tips[1])?$tips[1]:"" );
					break;
				case 6:
					$cols[$data['attr_name']]=
						new TextAreaColumn( $data['attr_name'],$data['attr_descr'],
											 $this->getReq($data), $data['attr_group'],
											 $data['attr_brief_msg'] );
					$cols[$data['attr_name']]->myGenType="chkboxes";
					if( is_numeric($data['attr_options2']) )
						$cols[$data['attr_name']]->myLimit=$data['attr_options2'];
					break;
				case 17:
					$cols[$data['attr_name']]=
						new AdTextColumn(  $data['attr_name'],  $data['attr_descr'],
											$this->getReq($data),$data['attr_group'],
											$data['attr_brief_msg'] );
					$cols[$data['attr_name']]->myGenType="chkboxes";
					break;
				case 7:
					$cols[$data['attr_name']]=
						new EmailColumn(   $data['attr_name'], $data['attr_descr'],
											$data['attr_req']=='t',$data['attr_req_msg'],
											$data['attr_group'],$data['attr_brief_msg'] );
					break;
				case 8:
					$cols[$data['attr_name']]=
						new BooleanColumn( $data['attr_name'],$data['attr_descr'],
											$this->getReq($data),$data['attr_group'],
											$data['attr_brief_msg'] );
					break;
				case 9:
					$arr=preg_split( "/,/", $data['attr_options'] );
					$arr_descr=preg_split( "/,/", $data['attr_options_msg'] );
					$mycols=array();
					for( $i=0; $i<sizeof($arr); $i++ )
					{
						array_push( $mycols,
							new BooleanColumn( $data['attr_name']."_".$arr[$i],$arr_descr[$i],
											   $this->getReq($data),$data['attr_group'],
											   $arr_descr[$i])
						);
					}
					$cols[$data['attr_name']]=
						new GroupTableOutputColumn( $data['attr_name'], $data['attr_descr'],
													 $mycols,true,NULL,$data['attr_brief_msg'],
									is_numeric($data['attr_options2'])?$data['attr_options2']:3 );

					$cols[$data['attr_name']]->myGenType="chkboxes";
					break;
				case 19:
					$arr=preg_split( "/,/", $data['attr_options'] );
					$arr_descr=preg_split( "/,/", $data['attr_options_msg'] );
					$mycols=array();
					for( $i=0; $i<sizeof($arr); $i++ )
					{
						array_push( $mycols,
							new BooleanColumn( $data['attr_name']."_".$arr[$i],$arr_descr[$i],
											   $this->getReq($data),$data['attr_group'],
											   $arr_descr[$i]) );
					}
					$cols[$data['attr_name']]=
						new GroupTableOutputColumn( $data['attr_name'],$data['attr_descr'],
													 $mycols,true,NULL,$data['attr_brief_msg'],
									is_numeric($data['attr_options2'])?$data['attr_options2']:3,
									"hidden" );

					$cols[$data['attr_name']]->myGenType="chkboxes";
					break;
				case 10:
					$cols[$data['attr_name']]=
						new ListDBColumn(  $data['attr_name'],$data['attr_descr'],
											$this->getReq($data),
											"v_buildings_projects","bp_id","bp_name",
											" WHERE lang='$LANG' ",$data['attr_group'],
											$data['attr_brief_msg'] );
					break;
				case 11:
					$arr_descr=preg_split( "/,/", $data['attr_options_msg'] );
					$cols[$data['attr_name']]=
						new ListColumn( $data['attr_name'],$data['attr_descr'],
										 $this->getReq($data),$arr_descr,
										 $data['attr_group'],$data['attr_brief_msg'] );
					break;
				case 13:
					$cols[$data['attr_name']]=
						new BaseColumn( $data['attr_name'],$data['attr_descr'],
										 true,NULL,false,$data['attr_brief_msg'] );

					$cols[$data['attr_name']]->myGenType="separator";
				case 14:
					//regions column
					break;
				case 15:
					$tips=preg_split( "/,/", $data['attr_tooltip'],2 );
					$cols[$data['attr_name']]=
						new AreaColumn( $data['attr_name'],$data['attr_descr'],
										 $this->getReq($data),$data['attr_brief_msg'],
										 isset($tips[0])?$tips[0]:"",isset($tips[1])?$tips[1]:"" );
					break;
				default:
					$err=true;
					break;
			}

			if( !$err && $this->processDynColumnAfter( $cols[$data['attr_name']],$data ) )
			{
				$cols[$data['attr_name']]->myToolTip=$data['attr_tooltip'];
				$cols[$data['attr_name']]->myIsBriefInTop=$data['is_brief_in_top']=='t';
				if( $data['attr_issort']=='t' )
				{
					$cols[$data['attr_name']]->mySortName=$data['attr_sortbyname'];
					$sorts[$data['attr_name']]=$data['attr_name'];
				}
			}

		}
		$this->addSortColumns( $sorts );
		return $cols;
	}


	protected function getStaticColumns( )
	{
		global $LANG,$langdata;
		return array();
	}

	protected function processDynColumnBefore( &$coldata )
	{
		return true;
	}

	protected function processDynColumnAfter( &$col, &$data )
	{
		return true;
	}

	private function getReq( &$data )
	{
		return (isset($data['attr_req']) && $data['attr_req']=='t')?$data['attr_req_msg']:NULL;
	}

//	protected function restoreCache( $id )
//	{
//	}
//
//	protected function cacheData( $id )
//	{
//	}

	function isImage( &$row,$rebuild=false )
	{
		if( $rebuild )
		{
			$ret=false;
			foreach( $this->myPictureColumns as $col )
			{
				$ret=$ret||$this->myColumns[$col]->isAnyImages( $row );
			}
		}
		else
			$ret=$row['brief_is_photo']=='t';
		return $ret;
	}

	function isImageForm( )
	{
		$ret=false;
		foreach( $this->myPictureColumns as $col )
		{
			$ret=$ret||$this->myColumns[$col]->isAnyImagesForm( $this->myData );
		}
		return $ret;
	}

	function getFirstImagePreview( &$row,$rebuild=false )
	{
		if( $rebuild )
		{
			foreach( $this->myPictureColumns as $col )
			{
				if( $this->myColumns[$col]->isAnyImages($row) )
				{
					return $this->myColumns[$col]->getFirstImagePreview( $row );
				}
			}
		}
		else
			$ret=$row['brief_photo'];
		return $ret;
	}
}
