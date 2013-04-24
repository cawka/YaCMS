<?php

class ListColumn extends BaseColumn 
{
	public $myOptions;
	public $myClass;
	protected $myExtraStuff="";
	protected $myFirstElements=array();
	public $myAdditional="";
	
	function __construct( $name,$descr,$required=NULL,$options=array(),$brief=false,$brmsg="",$class="" )
	{
		$this->myOptions=$options;
		$this->myClass=$class;
		
		parent::__construct( $name,$descr,true,$required,$brief,$brmsg );
	}
	
	function getValue( &$row )
	{
		return $row[$this->myName];
	}
	
	function checkBeforeSave( &$request )
	{
		return isset( $this->myOptions[$request[$this->myName]] );
	}
		
	function getInput( &$row )
	{
		if( sizeof($this->myOptions)==0 ) return ""; //nothing to select
		$ret="<select class='addann_select$this->myClass' name='$this->myName' ";
		if( $this->myToolTip!="" ) $ret.=" title='$this->myToolTip' ";
		$ret.=$this->myExtraStuff;
		$ret.=" $this->myAdditional >\n";

		$ret.=$this->getInputOptions( $this->myFirstElements,$this->getValue($row) );
		$ret.=$this->getInputOptions( $this->myOptions,      $this->getValue($row) );

		$ret.="</select>\n";

		return $ret;
	}

	private function getInputOptions( $assoc, $selected_item )
	{
		$ret="";
		foreach( $assoc AS $key=>$value )
		{
			$ret.="<option value='$key'";
			if( "$key"==$selected_item ) $ret.=" selected='selected'";
			$ret.=">$value</option>\n";
		}
		return $ret;
	}

	
	function extractValue( &$row )
	{
		return $this->myOptions[$row[$this->myName]];
	}

	function getXML( &$row )
	{
		$ret="<!-- $this->myDescription \n";
		foreach( $this->myOptions AS $key=>$value )
		{
			$ret.="$key=>\"$value\" "; 
		}
		$ret.="-->\n";

		return $ret."<$this->myName>".$this->extractXMLValue($row)."</$this->myName>";
	}
}
