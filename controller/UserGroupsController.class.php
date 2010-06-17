<?php

class UserGroupsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"users/groups.tpl","","common/form.tpl"
		);
	}
}

?>
