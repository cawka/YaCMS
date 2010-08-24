<?php

class RequestAction
{
	var $myClass;
	var $myTemplate;
	
	/**
	 * Authentification and Authorization Helper
	 *
	 * @var AuthHelper
	 */
	var $myAuth;
	
	function RequestAction( $controller,&$class, $nodelay=true )
	{
		global $DB,$LANG,$Auth;
		$this->myTemplate=new MySmarty( );
		
		$Auth=new AuthHelper( $controller );
		$this->myAuth=&$Auth;
		
		$this->myTemplate->assign( "Auth", $this->myAuth );

		if( $_REQUEST['action']=='' ) $_REQUEST['action']='index';
		if( $nodelay ) $this->parseInput( $class );
		
	}
	
	function parseInput( &$class )
	{
		$this->myTemplate->assign( "helper", $class->myHelper );
		
		if( isIn($_REQUEST['action'],get_class_methods($class)) ) 
		{
			$this->myAuth->allowOrRedirect( $_REQUEST['action'] );
			$class->myAction=$_REQUEST['action'];

			call_user_func_array( array($class,$_REQUEST['action']),
								  array(&$this->myTemplate,&$_REQUEST) );
		}
		else
			return $this->actionUndefined( );
	}
	
	function actionUndefined( )
	{
		$this->myTemplate->assign( "error", "action '$_REQUEST[action]' undefined" );
		$this->myTemplate->display( "common/error.tpl" );
	}
}

?>
