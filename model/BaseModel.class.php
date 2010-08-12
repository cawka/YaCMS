<?php

class BaseModel
{
	public $myPhp;
	public $myColumns=array();
	public $myData;
	public $myHelper;
	
	public function __construct( $php )
	{
		$this->myPhp=$php;
	}

	public function addColumns( $columns )
	{
		$this->myColumns=array_merge( $this->myColumns, $columns );
	}

	public function dataFromRequest( &$request )
	{
		$this->myData=$request;
	}
	
	public function collectData( &$request )
	{
		$this->myData=array( );
	}
	
	public function getRowToEdit( &$request )
	{
		$this->dataFromRequest( $request );
	}

	public function getRowToShow( &$request )
	{
		$this->dataFromRequest( $request );
	}
	
	public function showBrief( &$row )
	{
		$ret="";
		foreach( $this->myColumns as $col )
		{
			if( $col->myIsBrief ) 
			{ 
				$ret.=$row[$col->myName]." ";
			}
		}
		return $ret;
	}
	
	public function validateSave( &$request )
	{
		foreach( $this->myColumns as $col )
		{
			if( $col->myIsReadonly ) continue;

			$ret=$col->checkBeforeSave( $request );
			if( !$ret ) 
			{
				return $col->myError;
			}
		}

		return "";
	}
	
	protected function getColumnParams( $params=array() )
	{
		foreach( $this->myColumns as $col )
		{
			if( !$col->myIsVisible && !isset($col->myIsProtected) ) 
				$params[$col->myName]=$col->getValue( $this->myData );
		}
		return $params;
	}
	
	public function getFormCtrl( &$row,$action,$validate,&$options )
	{	
		return $this->myHelper->form_action( $this,$action,$validate,$this->getColumnParams(),$options );
	}
	
	function extractParentId( &$row )
	{
		return "";
	}

	protected function setColumnCommonData( )
	{
		foreach( $this->myColumns as &$col )
		{
			$col->myTable=&$this;
		}
	}

	public function getQuery( )
	{
//		$ret=urldecode( $_REQUEST['_p'] );
//		$ret2=http_build_query( $this->getColumnParams(),NULL,"&" );
//		if( $ret!="" && $ret2!="" ) $ret.="&";
//		return $ret.$ret2;
		return http_build_query( $this->getColumnParams(),NULL,"&" );
	}

	public function placeBlocks( $postion )
	{
	}
}
