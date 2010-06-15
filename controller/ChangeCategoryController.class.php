<?php

class ChangeCategoryController extends TableController
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","","common/form.tpl"
		);
	}

	public function index( &$tmpl, &$request )
	{
		return $this->edit( $tmpl, $request );
	}
}
