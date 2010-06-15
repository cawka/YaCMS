<?php

class Archieve2Model extends ArchieveModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		parent::__construct( $php );
		$this->myTableName="data";
	}
}

?>
