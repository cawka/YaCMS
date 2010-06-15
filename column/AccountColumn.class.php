<?php

class AccountColumn extends BaseColumn 
{
	function __construct( $name, $descr )
	{
		parent::__construct( $name,$descr,true,NULL,false,"",false );
		$this->mySQL=false;
	}
	
	function getInput( &$row )
	{
		global $langdata;
		
		$ret=parent::extractValue($row);
		$ret= ($ret!=""?$ret:"0.00")." грн";
		$ret.="";
		$ret.="<br/> <a href='/wallet.html'>$langdata[rahunok_popolnit]</a> ";
		$ret.="<br/> <a href='/mywallet'>$langdata[rahunok_history]</a> ";
		
		return $ret;
	}
	
	function extractValue( &$row )
	{
		return $this->getInput( $row );
	}
}
