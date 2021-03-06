<?php

class DateTimeColumn extends GroupColumn 
{
	function DateTimeColumn( $name,$descr,$required=NULL,$brief=false,$brmsg="" )
	{
		$hours=array(); $minutes=array(); $seconds=array();
		for( $i=0; $i<24; $i++ ) $hours[($i<10?"0":"")."$i"]=($i<10?"0":"")."$i";
		for( $i=0; $i<60; $i++ ) 
		{
			$minutes[($i<10?"0":"")."$i"]=($i<10?"0":"")."$i";
			$seconds[($i<10?"0":"")."$i"]=($i<10?"0":"")."$i";
		}
		
		parent::__construct( $name,$descr,array(
			"date"=>new DateColumn( $name."_date",""),
			"hour"=>new ListColumn( $name."_hour","",NULL,$hours,false,"","_other" ),
			"minute"=>new ListColumn( $name."_minute","",NULL,$minutes,false,"","_other" ),
			"second"=>new ListColumn( $name."_second","",NULL,$seconds,false,"","_other" ),
		),true,$required,$brief);
	}
	
	function getInsert( &$request )
	{
		if( !isset($request[$this->myName."_date"]) || $request[$this->myName."_date"]=="" )
			return "NULL";
		else
			return "'".$request[$this->myName."_date"]." ".$request[$this->myName."_hour"].":".$request[$this->myName."_minute"].":".$request[$this->myName."_second"]."'";

		
		return $this->myColumns['date']->getInsert( $request );
	}
	
	function getUpdate( &$request )
	{
		return $this->getUpdateName()."=".$this->getInsert( $request );
	}
	
	function getUpdateName( )
	{
		return $this->myName;
	}

	function getInput( &$row )
	{
		if( $row[$this->myName]!="" )
		{
			$row[$this->myName."_date"]=date("Y-m-d",strtotime($row[$this->myName]) );

			$row[$this->myName."_hour"]=date("H",strtotime($row[$this->myName]) );
			$row[$this->myName."_minute"]=date("i",strtotime($row[$this->myName]) );
			$row[$this->myName."_second"]=date("s",strtotime($row[$this->myName]) );
		}
		else 
		{
			$row[$this->myName."_date"]=date("Y-m-d");

			$row[$this->myName."_hour"]=date("H");
			$row[$this->myName."_minute"]=date("i");
			$row[$this->myName."_second"]=date("s");
		}
		$this->myData=$row;
//		print_r( $row );
//		die;
		
		return parent::getInput( $row );
	}

	public function getSQLType( )
	{
		return "timestamp";
	}
}

