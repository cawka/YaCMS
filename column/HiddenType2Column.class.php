<?php

class HiddenType2Column extends BaseColumn 
{
	function __construct( $name, $descr )
	{
		parent::__construct( $name,$descr,true,NULL );
	}
	
	function getInput( &$row )
	{
		return $row[$this->myName]."<input type='hidden' name='$this->myName' value='".$row[$this->myName]."' />";
	}
}

