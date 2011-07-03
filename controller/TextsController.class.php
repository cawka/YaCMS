<?php

class TextsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","admin/show_text.tpl","common/form.tpl"
		);
	}
}

