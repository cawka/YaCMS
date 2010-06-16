<?php

class MenuController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/menu.tpl","","common/form.tpl"
		);
	}
}

?>
