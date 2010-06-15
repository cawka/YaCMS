<?php

class JoobleModel extends BaseModel
{
	public function __construct( $php )
	{
		parent::__construct( $php );

		$this->myColumns=array(
				"phone" => new PhoneColumn( "phone", "phone" ),
				"price" => new PriceColumn( "zarplata", "zarplata" ),
			);
	}

	private function getLastId( $type )
	{
		global $DB;

		$last_value=$DB->GetOne( "SELECT set_value FROM settings WHERE set_name='jooble_$type'" );
		if( !$last_value ) $last_value=0;

		return $last_value;
	}

	private function setLastId( $type, $id )
	{
		global $DB;

		$DB->Execute( "UPDATE settings SET set_value=".$DB->qstr($id)." WHERE set_name='jooble_$type'" );
	}

	public function getVacancies( &$request )
	{
		global $DB;

		$last_value=$this->getLastId( "vacancies" );
		$res=$DB->Execute( 
			"SELECT d.*, a.* FROM ann_trebujetsjanarabotu a ".
			" JOIN ( SELECT * FROM data WHERE id>".$DB->qstr($last_value).
			" AND cat_tree ~ '*.132.*') d ON d.id=a.id ORDER BY d.id" );

		$this->myData=$res;//->GetRows( );

		if( sizeof($this->myData)>0 )
			$this->setLastId( "vacancies", $this->myData[ sizeof($this->myData)-1 ]['id'] );
	}

	public function getResumes( &$request )
	{
		global $DB;

		$last_value=$this->getLastId( "resumes" );
		$res=$DB->Execute( 
			"SELECT d.*, a.* FROM ann_isshurabotu a ".
			" JOIN ( SELECT * FROM data WHERE id>$last_value ".
			" AND cat_tree ~ '*.133.*') d ON d.id=a.id ORDER BY d.id" );

		$this->myData=$res;//->GetRows( );

		if( sizeof($this->myData)>0 )  
			$this->setLastId( "resumes", $this->myData[ sizeof($this->myData)-1 ]['id'] );
	}
}

