<?php

class AutoTableAttributesController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/autoTableAttributes.tpl","","common/form.tpl"
		);
	}

	protected function postSave( &$tmpl, &$request )
	{
		global $theAPC;
		$this->myModel->denormalize( );

		BaseHelper::clearCache( );

		return parent::postSave( $tmpl, $request );
	}
}

