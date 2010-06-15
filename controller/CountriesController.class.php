<?php

class CountriesController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/countries.tpl","","common/form.tpl"
		);
	}
}

?>
