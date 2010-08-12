<?php

class StaticTextColumn extends BaseColumn 
{
	public function __construct( $name, $description )
	{
		parent::__construct( $name, $description );
		$this->mySQL=false;
	}	

	public function extractValue( &$row )
	{
		return $row[$this->myName];
	}

	public function getInput( &$row )
	{
		return $this->extractValue( $row );
	}
}
