<?php

class UniqueColumn extends TextColumn
{
	protected $myTableName;
	protected $myErrorMsg;

	public function __construct( $name, $descr, $required=NULL, $table, $error )
	{
		parent::__construct( $name,$descr,$required,0,"","","",false,"",50 );

		$this->myTableName=$table;
		$this->myErrorMsg=$error;
	}
	
	function checkBeforeSave( &$request )
	{
		global $langdata,$DB;
		
		$data=$DB->GetOne( "SELECT $this->myName FROM $this->myTableName WHERE $this->myName=".$this->getInsert($request) );
		if( $data )
		{
			unset( $request[$this->myName] );
			$this->myError=$this->myErrorMsg;
			return false;
		}
		return true;
	}	
}

