<?php

class StaticPages2Controller extends TableController 
{
	public function __construct( &$model, &$helper )
	{
		parent::__construct( $model,$helper,"admin/staticpages_list.tpl",
											"common/static2.tpl",
											"common/form.tpl" );
	}
}
