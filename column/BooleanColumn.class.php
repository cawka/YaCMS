<?php

class BooleanColumn extends BaseColumn 
{
	var $myDescrRH;
	var $myBriefMsgRH;
	
	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="" )
	{
		$this->myDescrRH=$descr;
		$this->myBriefMsgRH=$brmsg;
		
		parent::__construct( $name,NULL,true,NULL,$brief,NULL );
	}
	
	function getInsert( &$request )
	{
		if( !isset($request[$this->myName]) || $request[$this->myName]=="" )
			return "'FALSE'";
		else
			return "'TRUE'";
	}

	function getValue( &$row )
	{
		if( isset($row[$this->myName]) && $row[$this->myName]=='t' ) 
			return "TRUE";
		else
			return "FALSE";
	}
	
	function getInput( &$row )
	{
		return "<input type='checkbox' name='$this->myName' ".
			   "value='t' ".($this->getValue($row)=='TRUE'?"checked":"")." />&nbsp;$this->myDescrRH";
	}

	function extractValue( &$row )
	{
		if( $row[$this->myName]=='t' ) 
			return $this->myBriefMsgRH;
		else 
			return "";
	}

	function getXML( $row )
	{
		$ret="<!-- $this->myBriefMsgRH: t=>True f=>False -->\n";
		return $ret.parent::getXML( $row );
	}

	function getSQLType( )
	{
		return "boolean";
	}
}

?>
