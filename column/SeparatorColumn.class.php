<?php

class SeparatorColumn extends BaseColumn
{
	public function __construct( $text )
	{
		parent::__construct( "", $text );
		$this->myGenType="separator";
		$this->mySQL=false;
	}
}

