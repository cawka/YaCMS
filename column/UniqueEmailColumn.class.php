<?php

class UniqueEmailColumn extends UniqueColumn
{
	public function __construct( $name, $descr, $required=NULL, $table, $error )
	{
		parent::__construct( $name, $descr, $required, $table, $error );

		array_push( $this->myValidate, "validate-email" );
	}

	function checkBeforeSave( &$request )
	{
		if( !EmailHelper::verifyEmail($request[$this->myName]) )
		{
			$this->myError="Invalid email address";
			return false;
		}

		return parent::checkBeforeSave( $request );
	}
}

