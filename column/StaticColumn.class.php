<?php

class StaticColumn extends BaseColumn 
{
	function __construct( $name, $descr )
	{
		parent::__construct( $name,$descr,true,NULL,false,"",false );
		$this->mySQL=false;
	}
	
	function getInput( &$request )
	{
		$ret="<span id='copy_$this->myName' ></span>
		<script type='text/javascript'>
			syncValues.periodical( 1000, [], [$('copy_$this->myName'),$('$this->myName')] );
		</script>";
		return $ret;
	}
//	function extractValue( &$row )
//	{
//		return "";
//	}
}
