<?php

class SitelinksModel extends BaseModel
{
	public $myTitle="";
	public $myAds;
	public $myCatalog;
	
	public function	 __construct( $php )
	{
		parent::__construct( $php );
	}

	public function collectAds( &$request, $page )
	{
		global $DB;

		$res=$DB->SelectLimit( "SELECT id,(CASE WHEN publ_modif IS NULL THEN publ_begin ELSE publ_modif END) as date FROM data ORDER BY publ_begin", 10000, $page*10000 );
		$this->myAds=$res;//->GetRows( );
	}

	public function collectCatalog( &$request, $page )
	{
		global $DB;

		$res=$DB->SelectLimit( "SELECT c.cat_id,cat_tree,date
			FROM catalog c
			LEFT JOIN (select cat_id,max(publ_begin) as date FROM data group by cat_id) d ON d.cat_id=c.cat_id
			ORDER BY date" );
		$this->myCatalog=$res;//->GetRows( );
	}
}

