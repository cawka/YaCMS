<?php

class SearchModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata,$SETTINGS;
		
		parent::__construct( $DB, $php, "data", array(
			new TextColumn(  "q",$langdata['search_words'],NULL,false,"","","<div class=\"ws_hint\">$langdata[search_help]</div>"),
			new CatalogColumn( "cat_id",$langdata['post_cattype_name'],NULL,10),
		"reg_id"=>	new RegionsColumn( "reg_id",$langdata['cat_reg_id'], NULL, 3 ),
			new ListColumn(  "date",$langdata['search_show_messages'],NULL,array(
				"all"=>$langdata['search_show_all'],
				"today"=>$langdata['search_show_today'],
				"week"=>$langdata['search_show_week'],
				"month"=>$langdata['search_show_month'],
			) ),
			new PhoneColumn( "phone",$langdata['cat_phone_num'],NULL,"",true ),
			new TextColumn( "uuid",$langdata['ad_uuid'] ),
			new EmailColumn( "email","Email",false ),
			new BooleanColumn( "withphoto",$langdata['only_with_photo'] ),
		),"id", true );

		$this->myColumns['reg_id']->blankLevelZero=false;

		if( isAdmin() ) $this->myColumns[]=new TextColumn( "from_ip","IP" );

		$this->myOrder="comm_up DESC,publ_begin DESC";

		$this->myElementsPerPage=$SETTINGS['advertisement_count'];
		if( isset($_REQUEST["rss"]) ) $this->myElementsPerPage=20;
	}

	public function prepareAdvanced( &$request )
	{
		$this->myData=$request;
	}

	private function collectUserInfo( &$request )
	{
		if( $request['user']!="" && (isAdmin() || $_SESSION['user']==$request['user']) )
		{
			$info=$this->myDB->GetRow( "SELECT * FROM users WHERE user_id=".$this->myDB->qstr($request[user]) );

			$this->myAdvCount=$info['u_adv_count'];
//			$this->myAdvCountCur=$info['u_adv_count_cur'];
		}
	}

	public function collectData( &$request )
	{
		global $LANG;
		
		$this->collectUserInfo( $request );

		$where=array("flag_category IS NULL");
		$order=" ORDER BY $this->myOrder ";

		switch( $request['date'] )
		{
			case "today":
				$where[]="publ_begin > NOW() - interval '1 day'";
				break;
			case "week":
				$where[]="publ_begin > NOW() - interval '1 week'";
				break;
			case "month":
				$where[]="publ_begin > NOW() - interval '1 month'";
				break;
		}

		if( $request["reg_id"]!="" )
		{
			$reg_id=preg_replace( '/\D/', '', $request['reg_id'] );
			$where[]="reg_tree ~ '*.$reg_id.*'";
		}

		if( $request["cat_id"]!="" )
		{
			$cat_id=preg_replace( '/\D/', '', $request['cat_id'] );
			$where[]="cat_tree ~ '*.$cat_id.*'";
		}
		
		if( $request["user"]!=""  )
		{
			$where[]="user_id=".$this->myDB->qstr( $request['user'] );
		}

		if( $request['phone_num']!="" )
		{
			$phone=preg_replace( '/\D/', '', $request['phone_num'] );
			$where[]="regexp_replace(phone_num,'\\D','','g')='$phone'";
//			$user_phone=str_replace( array("-"," "), array("",""), $request['phone_num'] );
//			$sPhone.=" AND ".$this->myDB->Concat("'8'","phone_code","phone_num")." LIKE '%$user_phone%' ";
		}

		if( $request["ip"]!="" )
		{
			$where[]="from_ip=".$this->myDB->qstr( $request['ip'] )."::inet ";
		}
		
		if( isset($request["withphoto"]) )
		{
			$where[]="(brief_photo IS NOT NULL AND brief_photo!='')";
		}
		
		if( $request["email"]!="" )
		{
			$where[]="email_copy=".$this->myDB->qstr( $request['email'] );
		}
		
		if( $request["uuid"]!="" )
		{
			$where[]="long_id=".$this->myDB->qstr( $request['uuid'] );
		}

		if( $request["q"]!="" )
		{
			$where[]="search_vec @@ q";
			$ts_query="plainto_tsquery('russian',".$this->myDB->qstr($request["q"]).")";
			$this->myTableName=" $ts_query q, $this->myTableName";
		}

		if( sizeof($where)==0 ) return; //do not allow empty requests
		$where=(sizeof($where)>0?" WHERE ":"").implode( " AND ", $where );

		if( $request['q']=="" )
		{
			$res=$this->collectDataBaseRaw( $request, "*", $where, $order );
		}
		else
		{
			// full-text search optimization hack
			$subquery="";
			if( $this->myIsOffset )
			{
				$count=$this->myDB->GetOne( "SELECT count(*) FROM $this->myTableName $where" );
				$offset=$this->getCurPage( $request,$count );

				$subquery="SELECT * FROM $this->myTableName $where $order ".
							"LIMIT $this->myElementsPerPage OFFSET ".$offset*$this->myElementsPerPage;
			}
			else
				$subquery="SELECT * FROM $this->myTableName $where $order";

			$res=$this->myDB->Execute(
"SELECT *,ts_headline('russian',brief$LANG,q,'StartSel=<b>,StopSel=</b>,HighlightAll=true') as brief$LANG ".
		"FROM ($subquery) d"
			);
		}

		$this->myData=$res;//->GetRows( );
	}
}
