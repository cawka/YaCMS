<?php

class UserRightsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/rights.tpl","",""
		);
	}
	
	public function edit( &$tmpl, &$request ) 
	{
		return $this->index( $tmpl, $request );
	}

	protected function postSave( &$tmpl, &$request )
	{
		global $theAPC;

		BaseHelper::clearCache( );
		return parent::postSave( $tmpl, $request );
	}
}
