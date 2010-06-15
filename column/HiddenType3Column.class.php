<?php

class HiddenType3Column extends BaseColumn
{
	var $myField;

	function __construct( $name,$field )
	{
		parent::__construct( $name,"",false,NULL );
		
		$this->myField=$field;
	}

	function getValue( &$row )
	{
		return $row[$this->myField];
	}

	function getInput( &$row )
	{
		if( $this->getValue($row)!==null )
		{
			return "<input type='hidden' name='$this->myName' value='".$this->getValue($row)."' />";
		}
		else
		{
			return "";
		}
	}
}

