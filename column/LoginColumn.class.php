<?php

class LoginColumn extends EmailColumn
{
	private $myTableName="users";
	
	function checkBeforeSave( &$request )
	{
		global $langdata,$DB;
		
		$data=$DB->GetOne( "SELECT $this->myName FROM $this->myTableName WHERE $this->myName=".$this->getInsert($request) );
		if( $data )
		{
			$this->myError=$langdata['reg_login_exists'];//"Такой логин уже существует, попробуйте другой";
			return false;
		}
		return true;
	}	
}
