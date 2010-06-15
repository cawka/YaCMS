<?php

/**
 * Controller of user profile. 
 *
 * !!! ATTENTION. Almost everything should not be allowed through permission module to normal users
 */
class UserProfileController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","","services/user_profile.tpl"
		);
	}
	
	public function index( &$tmpl, &$request )
	{
		$tmpl->caching=false;
		return $this->edit( $tmpl, $request );
	}
	
	protected function postSave( &$tmpl,&$request ) 
	{
		return $this->index( $tmpl, $request );
	}
}

?>
