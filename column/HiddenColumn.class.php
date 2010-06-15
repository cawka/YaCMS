<?php

class HiddenColumn extends BaseColumn 
{
	var $myValue;
	
	function __construct( $name,$value )
	{
		parent::__construct( $name,"",false,NULL );
		$this->myValue=$value;
	}
	
	function getValue( &$row )
	{
		return $this->myValue;
	}
	
	function getInput( &$row )
	{
		if( isset($this->myValue) )
		{
			return "<input type='hidden' name='$this->myName' value='".$this->getValue($row)."' />";
		}
		else  
		{
			return "";
		}
	}
	
	function extractValue( &$row )
	{
		return "";
	}
}

class HiddenColumnType2 extends BaseColumn 
{
	var $myValue;
	
	function __construct( $name )
	{
		parent::__construct( $name,"",false,NULL );
//		print $this->myName;
//		die;
	}
	
	function getValue( &$row )
	{
		return $row[$this->myName];
	}
	
	function getInput( &$row )
	{
		if( isset($this->myValue) )
		{
			return "<input type='hidden' name='$this->myName' value='".$this->getValue($row)."' />";
		}
		else  
		{
			return "";
		}
	}
}

