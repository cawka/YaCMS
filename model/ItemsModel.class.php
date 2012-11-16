<?php

class ItemsModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "items", array(
			new HiddenType2Column( "type", "Type" ),
			new TextColumn("bold_title","Bold title"),
			new TextColumn("title","Title"),
			new TextAreaColumn("descr","Description"),
			new TextColumn("link","Link (if available)"),
			new PhotoColumn("image","Image (if available)",NULL,0,0),
			new TextColumn("years","Years"),
			new IntegerColumn("msort","Manual Sort"),
			) );
		$this->myOrder="msort DESC, years DESC";

		$this->RefreshByReload=true;
//		$this->myElementsPerPage=30;
		
		$this->mySortColumns=array( 
			"msort"=>array("asc"=>"msort","desc"=>"msort DESC"),
		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}

	public function collectData( &$request )
	{
		$this->myExtraWhere="type=".$this->myDB->qstr($request['type']);
		return parent::collectData( $request );
	}
}

