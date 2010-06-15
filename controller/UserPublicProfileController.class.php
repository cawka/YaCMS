<?php

/**
 * Public user profiles
 * 
 * ATTENTION: only 'index' action should be accessed by everyone
 *
 */
class UserPublicProfileController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","services/user_public_profile.tpl",""
		);
	}
	
	public function index( &$tmpl, &$request )
	{
		if( !isset($request['user_id']) || !is_numeric($request['user_id']) ) return header( "Location: /" );
		return $this->show( $tmpl, $request );
	}
}

?>
