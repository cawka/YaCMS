<?php

class CatalogSelectorController extends BaseController
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}

	public function index( &$tmpl, &$request )
	{
		$this->showTemplate( $tmpl, $request, "ads/_catalog_selector.tpl" );
	}
}
