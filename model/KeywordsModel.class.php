<?php

class KeywordsModel extends TableModel
{
	public $myTitle="Search Keywords";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "keywords", array(
			new DateColumn("date","Date"),
			new TextColumn("ip","IP"),
			new TextColumn("engine","Engine"),
			new TextColumn("keywords","Keywords"),
			new TextColumn("referer", "Referer"),
		), "id",NULL,"",true );
		$this->myOrder="date DESC";
//		$this->myElementsPerPage=30;
	}
}

?>
