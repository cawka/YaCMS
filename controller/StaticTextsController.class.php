<?php

class StaticTextsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/static_texts.tpl","","common/form.tpl"
		);
	}
}

?>
