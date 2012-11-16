<?php

class ItemsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"common/items.tpl","","common/form.tpl"
		);
	}

	public function tex( &$tmpl,&$request )
	{
		return $this->showTemplate($tmpl, $request, "resume.tpl", "collectData" );
	}
}

