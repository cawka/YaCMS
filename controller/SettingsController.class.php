<?php

class SettingsController extends TableController 
{
	public function __construct( &$model, &$helper )
	{
		parent::__construct( $model,$helper,
			"admin/settings.tpl","","common/form.tpl"
		);
	}

	protected function postSave( &$tmpl, &$request )
	{
		global $theAPC;

		// settings are the hard-core stuff, so need to clean all caches
		BaseHelper::clearCache( );
		$theAPC->clear_all( );
		
		return parent::postSave( $tmpl, $request );
	}
}

?>
