<?php

class DoubleColumn extends TextColumn 
{
	var $myDefault;
	
	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$default=NULL,$length=0,$class="",$opt_msg="",$opt="" )
	{
		parent::__construct( $name,$descr,$required,$brief,$brmsg,$class,$opt_msg,false,$opt );
		
		array_push( $this->myValidate, "validate-numeric" );
		$this->myDefault=$default;
	}
	
	function checkBeforeSave( &$request ) 
	{
		$test=trim( $request[$this->myName] );
		return $test=="" || is_numeric( $test );
	}
	
	function getInsert( &$request )
	{
		if( !isset($this->myDefault) || is_numeric($request[$this->myName]) ) 
			return parent::getInsert( $request );
		else 
			return "'$this->myDefault'";
	}

	function extractBriefValue( &$row )
	{
		if( parent::extractBriefValue($row)!="" )
			return "<center>".parent::extractBriefValue($row)."</center>";
		else 
			return "";
	}
	
	function extractPreviewValue( &$row )
	{
		return parent::extractBriefValue( $row );
	}	

	function extractAdminValue( &$row )
	{
		return $this->extractPreviewValue( $row );
	}

	public function getSQLType( )
	{
		return "double precision";
	}
}

?>
