<?php

class PublicationTimeColumn extends ListColumn 
{
	private $myBeginTime="NOW()";
	
	function __construct( $name,$descr,$class="" )
	{
		global $langdata;
		
		parent::__construct( $name,$descr,$langdata["publ_req"],
		array(
			0=>$langdata["publ_day"],
			1=>$langdata["publ_3day"],
			2=>$langdata["publ_week"],
			3=>$langdata["publ_2week"],
			4=>$langdata["publ_month"],
			5=>$langdata["publ_2month"],
		),false,"",$class );
	}

	function getValue( &$row )
	{
		if( isset($row[$this->myName."_begin"]) &&
			isset($row[$this->myName."_end"]) )
		{
			$begin=strtotime( $row[$this->myName."_begin"] );
			$end  =strtotime( $row[$this->myName."_end"] );
			$sec=$end-$begin;
			
			if( $sec<=3600*24*2 )
				return 0;
			else if( 3600*24*2<=$sec && $sec<3600*24*5 )
				return 1;
			else if( 3600*24*5<=$sec && $sec<3600*24*10 )
				return 2;
			else if( 3600*24*10<=$sec && $sec<3600*24*20 )
				return 3;
			else if( $sec>=3600*24*20 && $sec<3600*24*50 )
				return 4;
			else if( $sec>=3600*24*50 ) 
				return 5;
			else
				return 5;
		}
		
		if( isset($row[$this->myName]) && is_numeric($row[$this->myName]) )
			return $row[$this->myName]; //only works for $_REQUEST data
		else 
			return 5; //maximum publication time
	}

	function getXML( &$row )
	{
		$ret="<!-- $this->myDescription -->\n";
		$ret.="<publ_begin>".$row['publ_begin']."</publ_begin>\n";
		$ret.="<publ_end>".$row['publ_end']."</publ_end>\n";

		return $ret;
	}

	function getUpdateName()
	{
		return $this->myName."_begin,$this->myName"."_end";
	}

	function getInsertEnd( &$request )
	{
		$ret="";
		
		switch( $request[$this->myName] )
		{
			case 0:
				return $ret."NOW()+interval '1 days'";
			case 1:
				return $ret."NOW()+interval '3 days'";
				break;
			case 2:
				return $ret."NOW()+interval '1 weeks'";
				break;
			case 3:
				return $ret."NOW()+interval '2 weeks'";
				break;
			case 4:
				return $ret."NOW()+interval '1 months'";
				break;
			default:
			case 5:
				return $ret."NOW()+interval '2 months'";
				break;
		}
	}

//	function setBeginTime( $datetime )
//	{
//		$this->myBeginTime="'".date( "Y-m-d H:M:S",$datetime )."'";
//	}
	
	function getInsert( &$request )
	{
		
		if( isset($request[$this->myName."_begin"]) )
			$begin="'".$request[$this->myName."_begin"]."'";
		else 
			$begin="NOW()";
		
		$ret="$begin,";
		return $ret.$this->getInsertEnd( $request );
	}
	
	function getUpdate( &$request )
	{
		return "$this->myName"."_end=".$this->getInsertEnd( $request );
	}
}
