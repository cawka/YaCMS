<?php

class PopupController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","common/popup.tpl","common/form.tpl"
		);
	}
}

?>
