<?php

class UserGroupsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/groups.tpl","","common/form.tpl"
		);
	}
}

?>
