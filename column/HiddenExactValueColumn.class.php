<?php

class HiddenExactValueColumn extends HiddenColumn
{
	public $myQuote=true;

	public function __construct( $name,$value,$quote=true )
	{
		parent::__construct( $name, $value );
		$this->myQuote=$quote;
	}

	function getInsert( &$req )
	{
		global $DB;

		if( $this->myQuote )
			return isset($this->myValue)?$DB->qstr($this->myValue):"NULL";
		else
			return $this->myValue;
	}
}
