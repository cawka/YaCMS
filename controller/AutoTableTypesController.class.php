<?php

class AutoTableTypesController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/autoTableTypes.tpl","","common/form.tpl"
		);
	}
}

