<?php

class PriceColumn extends BaseColumn 
{
	var $CURRENCY=array( "$","€"," грн." );
	var $myOptionMsg;
	var $myAutoCalc=true; // use javascript to forward price to calculator
	var $myValidate=array();

	public function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$opt_msg="",$calc=1 )
	{
		parent::__construct( $name,$descr,true,$required,$brief,$brmsg );
		
		$this->myOptionMsg=$opt_msg;
		$this->myGenType="price";
		$this->myAutoCalc=(isset($calc) && $calc!="")?$calc:1;

		if( isset($required) ) array_push( $this->myValidate, "required" ); 
		array_push( $this->myValidate, "validate-numeric" );
	}
	
	function getValue( &$row,$value=true ) //if false - currency
	{
		if( $value )
			return $row[$this->myName];
		else 
			return $row[$this->myName."_cur"];
	}

	function getXML( $row )
	{
		$ret="<!-- $this->myDescription -->";
	}
	
	function getInput( &$row )
	{
		$classes=array("addann_price");
		if( $this->myToolTip!="" ) array_push( $classes, "tooltip" );
		$classes=array_merge( $classes, $this->myValidate );
				
		$ret="<input class=\"".implode(" ", $classes)."\" type='text' name='$this->myName' value='".$this->getValue($row,true)."' />";
		$ret.="&nbsp;<select name='$this->myName"."_cur' class=\"addann_currency\">";
		for( $i=0; $i<sizeof($this->CURRENCY); $i++ )
		{
			$ret.="<option value='$i' ".($this->getValue($row,false)==$i?"selected='selected'":"").">".$this->CURRENCY[$i]."</option>\n";
		}
		$ret.="</select>"." ".$this->myOptionMsg;
		return $ret;
	}
	
	function getUpdate( &$request )
	{
		$ret="$this->myName=".$this->getInsertSpec($request,0).",".
			 "$this->myName"."_cur=".$this->getInsertSpec($request,1);
		return $ret;
	}
	
	function getUpdateName( )
	{
		$ret=$this->myName;
		$ret.=",".$this->myName."_cur";
		return $ret;
	}
	
	function getInsertSpec( &$request,$val )
	{
		$ret="";
		if( !isset($request[$this->myName]) || $request[$this->myName]=="" )
			$ret.="NULL";
		else
			$ret.="'".$request[$this->myName]."'";
		if( $val==0 ) return $ret;
			
		$ret2="'".$request[$this->myName."_cur"]."'";
		if( $val==1 ) return $ret2;
	}
	
	function getInsert( &$request )
	{
		return $this->getInsertSpec($request,0).",".$this->getInsertSpec($request,1);
	}
	
	function extractValue( &$row )
	{
		if( $row[$this->myName]!="" )
		{
			$ret.=str_replace( " ", "&nbsp;", number_format($row[$this->myName],0,"."," "))."".$this->CURRENCY[$row[$this->myName."_cur"]]." ".$this->myOptionMsg;
			switch( $this->myAutoCalc )
			{
			case 1:
				$ret.="<script>setPrice('".$row[$this->myName]."',".$row[$this->myName."_cur"].",'".$this->CURRENCY[$row[$this->myName."_cur"]]."','20')</script>";
				break;
			case 2:
				$ret.="<script>setPrice('".$row[$this->myName]."',".$row[$this->myName."_cur"].",'".$this->CURRENCY[$row[$this->myName."_cur"]]."','5')</script>";
				break;
			case 3:
				$ret.="<script>setPrice('".$row[$this->myName]."',".$row[$this->myName."_cur"].",'".$this->CURRENCY[$row[$this->myName."_cur"]]."','1')</script>";
				break;
			default:
				break;
			}
			return $ret;
		}
		else
			return "";
	}

	function extractBriefValue( &$row )
	{
		if( $row[$this->myName]!="" )
		{
			$ret=str_replace( " ", "&nbsp;", number_format($row[$this->myName],0,"."," "))."".$this->CURRENCY[$row[$this->myName."_cur"]];
			if( isset($this->myOptionMsg) && $this->myOptionMsg!="" ) $ret.=" ".$this->myOptionMsg;
			return $ret;
		}
		else 
			return "";
	}
	
	function extractPreviewValue( &$row )
	{
		return $this->extractBriefValue( $row );
	}	

	function extractAdminValue( &$row )
	{
		return $this->extractPreviewValue( $row );
	}
}
