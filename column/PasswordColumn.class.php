<?php

class PasswordColumn extends TextColumn 
{
	var $myEqualTo="";
	var $myPrimary=false;
	
	function __construct( $name,$descr,$required=NULL,$brief=false,$brmsg="",$primary=true,$checkto="" )
	{
		parent::__construct( $name,$descr,$required,$brief,$brmsg, "","",0,"", 50 );
		
		if( $checkto!="" ) 
		{
			if( !$primary ) $this->myValidate=array_merge( $this->myValidate, 
				array("validate-match", "matchInput:'$checkto'")  );
			
			$this->myEqualTo=$checkto;
		}
		
		$this->htmlType="password";
		$this->mySQL=$primary;
		$this->myPrimary=$primary;
	}
	
	function checkBeforeSave( &$request )
	{
		global $langdata;
		
		if( $this->myPrimary ) 
		{
			if( isset($this->myRequired) )
			{
				if(	$request[$this->myName]=="" )
				{
					$this->myError="Password is required";
					return false;
				}
			}
			else if( $request[$this->myName]=="" )
			{
				$this->mySQL=false; //do not update password if it wasn't entered
			}

			if( $request[$this->myName]!="" && strlen($request[$this->myName])<5 )
			{
				$this->myError="Password should be at least 5 characters long";
                   return false;
			}

			return true;
		}
		
		if( $request[$this->myName]!=$request[$this->myEqualTo] )
		{
			$this->myError="Password mistmatch";
			return false;
		}
		return true;
	}

	function getUpdateName( )
	{
		return $this->myName."_md5";
	}

	function getInsert( &$request )
	{
		global $DB;
		if( !isset($request[$this->myName]) || $request[$this->myName]=="" )
			return "NULL";
		else
		{
			return $DB->qstr( md5($request[$this->myName]) );
		}
	}
	
	function getValue( &$row )
	{
		if( !$this->myPrimary )
		{
			$tmp=$this->myName;
			$this->myName=$this->myEqualTo;
			$ret=parent::getValue( $row );
			$this->myName=$tmp;
			return $ret;
		}
		else
			return parent::getValue( $row );
	}

	function extractValue( &$row )
	{
		if( !$this->mySQL ) return "";
		return parent::extractValue( $row );
	}
}
