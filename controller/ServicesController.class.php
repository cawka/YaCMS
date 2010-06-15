<?php

class ServicesController extends TableController 
{
	public function __construct( &$model, &$helper )
	{
		parent::__construct( $model,$helper,
							 "admin/services.tpl", "", "common/form.tpl" );
	}

	protected function postSave( &$tmpl, &$request )
	{
		global $theAPC;

		// services are also the hard-core stuff, so need to clean all caches
		BaseHelper::clearCache( );
		$theAPC->clear_all( );

		return parent::postSave( $tmpl, $request );
	}

}

?>