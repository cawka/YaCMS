<?php

class DateColumn extends BaseColumn 
{
	private $myDefault="";

	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$default="" )
	{
		parent::__construct( $name,$descr,true,$required,$brief,$brmsg );

		$this->myDefault=$default;
	}
	
	function getValue( &$row )
	{
		$ret=$row[$this->myName];
		return $ret;
	}
	
	function getInput( &$row )
	{
		if( $this->getValue($row)!="" )
		{
			$value=date("Y-m-d",strtotime($this->getValue($row)) );
		}
		else 
		{
			$value=$this->myDefault;
			//$value=date("Y-m-d");
		}

		$ret="<input type='text' class='datepicker i_int";
		if( isset($this->myRequired) ) $ret.=" required";
		$ret.="' title='$this->myRequired' ".
			 " name=\"$this->myName\" id=\"$this->myName\" value='$value' />";

		return $ret;
	}

	function getSQLType( )
	{
		return "date";
	}
}

