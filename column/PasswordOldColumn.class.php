<?php

class PasswordOldColumn extends TextColumn
{
	private $myTable;
	private $myTableField;

	public function __construct( $name,$descr,$required=NULL, $table, $table_field )
	{
		parent::__construct( $name,$descr,$required,false,"", "","",0,"", 50 );
		$this->mySQL=false;
		$this->htmlType="password";

		$this->myTable=$table;
		$this->myTableField=$table_field;
	}

	public function checkBeforeSave( &$request )
	{
		global $DB;
	
		if( !$this->myIsRequired && $request[$this->myName]=='' )
			return true;

		if( $request[$this->myName]=='' )
		{
			$this->myError='You should specify an old password';
			return false;
		}

		$ret=$DB->GetOne( "SELECT $this->myTableField FROM $this->myTable WHERE $this->myTableField=".
							$DB->qstr( md5($request[$this->myName]) ) );
		
		if( !$ret )
		{
			$this->myError='Incorrect old password';
			return false;
		}

		return true;
	}
}

