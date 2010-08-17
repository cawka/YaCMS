<?php

function utf8_substr($str,$from,$len){
# utf8 substr
# www.yeap.lv
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}

function utf8_strlen($str) {
  return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $dummy);
}

class TextColumn extends BaseColumn 
{
	var $myAdditional="";
	var $myLimit=1048576; // a huge limit
	var $myClass="";
	var $myOptionMsg="";
	var $myIsOptionBrief=true;
	var $myValidate=array();
	protected $htmlType="text";
	
	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$class="",$opt_msg="",$readonly=false,$opt="", $limit=1048576 )
	{
		parent::__construct( $name,$descr,true,$required,$brief,$brmsg,$readonly );
		$this->myClass=$class;
		$this->myOptionMsg=$opt_msg;
		$this->myLimit=$limit;
		if( $opt!="" ) $this->myIsOptionBrief=false;
		if( $required ) array_push( $this->myValidate, "required" );
	}
	
	function getValue( &$row )
	{
		/// @bug: Add extra 20 symbols as a some kind of solution to the problem of incorre
		$ret=$row[$this->myName];
		if( $this->myLimit>0 && utf8_strlen($ret)>$this->myLimit+20 ) $ret=utf8_substr( $ret,0,$this->myLimit+20 );
		return $ret;
	}

	function getInsert( &$request )
	{
		global $DB;
		$ret=$request[$this->myName];
		if( $this->myLimit>0 && isset($ret) && utf8_strlen($ret)>$this->myLimit ) $ret=utf8_substr( $ret,0,$this->myLimit );
		
		if( !isset($ret) || $ret=="" )
			return "NULL";
		else
			return $DB->qstr( stripslashes($ret) );
	}
	
	function getInput( &$row )
	{
		$classes=array("i$this->myClass");
		if( $this->myToolTip!="" ) array_push( $classes, "tooltip" );
		$classes=array_merge( $classes, $this->myValidate );
		
		$ret="<input id='$this->myName' class=\"".implode(" ", $classes)."\" type='$this->htmlType' ".
			 " name='$this->myName' value=\"".htmlentities($this->getValue( $row ))."\" ";

		if( $this->myToolTip!="" ) $ret.=" title='$this->myToolTip' ";
		if( $this->myLimit>0 ) $ret.=" MAXLENGTH='$this->myLimit' ";
		$ret.= " $this->myAdditional />";
		if( $this->myOptionMsg!="" ) $ret.=" $this->myOptionMsg";
		   	   
		return $ret;
	}
	
	function extractValue( &$row )
	{
		if( parent::extractValue($row)!="" )
			return htmlentities( parent::extractValue( $row ),ENT_NOQUOTES,"UTF-8" )." ".$this->myOptionMsg;
		else
			return "";
	}
	
	function extractBriefValue( &$row )
	{
		if( parent::extractValue($row)!="" )
		{
			$ret=htmlentities( parent::extractValue( $row ),ENT_NOQUOTES,"UTF-8" );
			if( utf8_strlen($ret)>100 ) $ret=utf8_substr( $ret,0,100 )."...";
			if( $this->myIsOptionBrief ) $ret.=" ".$this->myOptionMsg;
			return $ret;
		}
		else
			return "";
	}
	
	function extractPreviewValue( &$row )
	{
		return $this->extractBriefValue( $row );
	}

        function getXML( $request )
	{
		$ret="<!-- $this->myDescription -->\n";
                return $ret."<$this->myName><![CDATA[".$this->extractXMLValue($request)."]]></$this->myName>\n";
        }	
}
