<?php

class CountriesModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "countries", array(
				new TextColumn( "code","2-letter code" ),
				new TextColumn( "name","Country name (in Lativan)" ),
			), "id",false );
	}
}

?>
