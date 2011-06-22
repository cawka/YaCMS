<?php

class TextsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","admin/show_text.tpl","common/form.tpl"
		);
	}

	public function postSave( &$tmpl, &$request )
	{
		$tmpl->clearAllCache( );
		return parent::postSave( $tmpl, $request );
	}

	public function postDelete( &$tmpl, &$request )
	{
		$tmpl->clearAllCache( );
		return parent::postDelete( $tmpl, $request );
	}
}

