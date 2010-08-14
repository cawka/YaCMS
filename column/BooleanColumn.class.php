<?php

class BooleanColumn extends BaseColumn 
{
	public $myDescrRH;
	public $myBriefMsgRH;
	public $myClass;
	
	public function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$class="" )
	{
		$this->myDescrRH=$descr;
		$this->myBriefMsgRH=$brmsg;
		$this->myClass=$class;
		
		parent::__construct( $name,NULL,true,NULL,$brief,NULL );
	}
	
	public function getInsert( &$request )
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

	public function getValue( &$row )
	{
		if( isset($row[$this->myName]) && ($row[$this->myName]=='t' || $row[$this->myName]=='1') )
			return "TRUE";
		else
			return "FALSE";
	}
	
	public function getInput( &$row )
	{
		$ret="<input type='checkbox' id='$this->myName' name='$this->myName' class='$this->myClass' ".
			"value='t' ".($this->getValue($row)=='TRUE'?"checked":"")." />";
		if( $this->myDescrRH!="" ) $ret.="&nbsp;$this->myDescrRH";

		return $ret;
	}

	public function extractValue( &$row )
	{
		if( $row[$this->myName]=='t' || $row[$this->myName]=='1')
			return $this->myBriefMsgRH;
		else 
			return "";
	}

	public function getXML( $row )
	{
		$ret="<!-- $this->myBriefMsgRH: t=>True f=>False -->\n";
		return $ret.parent::getXML( $row );
	}

	public function getSQLType( )
	{
		return "boolean";
	}
}

?>
