<?php

class IntegerColumn extends TextColumn 
{
	var $myDefault;
	var $myLength=0;
	
	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$default=NULL,$length=0,$class="",$opt_msg="",$opt="" )
	{
		parent::__construct( $name,$descr,$required,$brief,$brmsg,$class,$opt_msg,false,$opt );
		
		array_push( $this->myValidate, "validate-integer" );
		$this->myDefault=$default;
		$this->myLimit=$length;
		$this->myLength=$length;
	}
	
	function checkBeforeSave( &$request ) 
	{
		$test=trim( $request[$this->myName] );
		if( $this->myLength>0 && strlen($test)>$this->myLength ) return false;

		for( $i=0; $i<strlen($test); $i++ )
		{
			if( !('0'<=$test{$i} && $test{$i}<='9') ) return false;
		}
		return true;
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
		return "integer";
	}
}

?>
