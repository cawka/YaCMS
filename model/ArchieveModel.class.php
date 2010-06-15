<?php

class ArchieveModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "data_archieve", array(), "id",true );
		$this->myOrder="publ_begin DESC";
		$this->myElementsPerPage=150;

	}
}
