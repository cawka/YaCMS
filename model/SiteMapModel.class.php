<?php

class SiteMapModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$LANG,$langdata;
		
		parent::__construct( $DB,$php, "(SELECT * FROM catalog_lang$LANG WHERE cat_cat_id IS NULL) site_map", array(
			) );
		$this->myOrder="cat_order";
//		$this->myElementsPerPage=30;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}
	
	public function collectSubLevel( &$cat )
	{
		global $LANG;
		$this->myTableName="(SELECT * FROM catalog_lang$LANG WHERE cat_cat_id=".$this->myDB->qstr( $cat['cat_id'] ).") site_map";
		$a=array();
		return $this->collectDataBase( $a );
	}
}

?>
