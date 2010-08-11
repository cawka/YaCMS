<?php

class GroupColumn extends BaseColumn 
{
	/**
	 * Array of columns
	 *
	 * @var BaseColumn[]
	 */
	var $myColumns;
	var $myExtractDelimeter=", ";
	
	function __construct( $name,$descr,$columns,$visible,$req,$brief )
	{
		parent::__construct( $name,$descr,$visible,$req,false,$brief );
		$this->myColumns=$columns;
		$this->myGenType="";
	}
	
	function checkBeforeSave( &$request )
	{
		$ret=true;
		foreach( $this->myColumns as $val )
		{
			$ret=$ret && $val->checkBeforeSave( $request );
		}
		return $ret;
	}
	
	function getInsert( &$request )
	{
		$ret="";
		foreach( $this->myColumns as $col )
		{
			if( $ret!="" ) $ret.=",";
			$ret.=$col->getInsert( $request );
		}
		return $ret;
	}
	
	function getUpdate( &$request )
	{
		$ret="";
		foreach( $this->myColumns as $col )
		{
			if( $ret!="" ) $ret.=",";
//			$ret.=$col->myName."=".$col->getInsert( $request );
			$ret.=$col->getUpdate( $request );
		}
		return $ret;
	}
	
	function getUpdateName( )
	{
		$ret="";
		foreach( $this->myColumns as $col )
		{
			if( $ret!="" ) $ret.=",";
			$ret.=$col->getUpdateName( );
		}
		return $ret;
	}
		
	function getInput( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col )
		{
			//if( $ret!="" ) $ret.=",";
			$ret.=$col->myDescription." ".$col->getInput( $row )." ";
		}
		return $ret;
	}
	
	function extractValue( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col ) 
		{
			$data=$col->extractValue( $row );
			if( $data=="" ) continue;
			if( $ret!="" ) $ret.=$this->myExtractDelimeter;
			$ret.=$data;
		}
		return $ret;
	}	

	function extractBriefValue( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col ) 
		{
			$data=$col->extractBriefValue( $row );
			if( $data=="" ) continue;
			if( $ret!="" ) $ret.=$this->myExtractDelimeter;
			$ret.=$data;
		}
		return $ret;
	}	

	function extractPreviewValue( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col ) 
		{
			$data=$col->extractPreviewValue( $row );
			if( $data=="" ) continue;
			if( $ret!="" ) $ret.=$this->myExtractDelimeter;
			$ret.=$data;
		}
		return $ret;
	}	
	
	function extractAdminValue( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col ) 
		{
			$data=$col->extractAdminValue( $row );
			if( $data=="" ) continue;
			if( $ret!="" ) $ret.=$this->myExtractDelimeter;
			$ret.=$data;
		}
		return $ret;
	}

	function getXML( &$row )
	{
		$ret.="<!-- $this->myDescription -->\n";
		$ret.="<$this->myName>\n";
		foreach( $this->myColumns as $col )
		{
			$ret.="\t".$col->getXML( $row );
		}
		$ret.="</$this->myName>\n";
		return $ret;
	}
}
