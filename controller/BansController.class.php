<?php

class BansController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/bans.tpl","","common/form.tpl"
		);
	}
}

?>
