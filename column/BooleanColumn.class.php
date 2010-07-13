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
		{
			if( DB_ENGINE=="postgres" )
				return "'FALSE'";
			else
				return "0";
		}
		else
		{
			if( DB_ENGINE=="postgres" )
				return "'TRUE'";
			else
				return "1";
		}
	}

	function getValue( &$row )
	{
		if( isset($row[$this->myName]) && ($row[$this->myName]=='t' || $row[$this->myName]=='1') )
			return "TRUE";
		else
			return "FALSE";
	}
	
	function getInput( &$row )
	{
		return "<input type='checkbox' id='$this->myName' name='$this->myName' ".
			   "value='t' ".($this->getValue($row)=='TRUE'?"checked":"")." />&nbsp;$this->myDescrRH";
	}

	function extractValue( &$row )
	{
		if( $row[$this->myName]=='t' || $row[$this->myName]=='1')
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
