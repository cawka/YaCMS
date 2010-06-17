<?php

class LoginController extends BaseController 
{
	public function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl,$request,"users/login.tpl","get_default_login" );
	}
	
	public function logout( &$tmpl, &$request )
	{
		$this->myModel->clearSessionData( );	
		
		if( isset($request['redirect']) )
		{
			header( "Location: $request[redirect]" );
			exit;
		}
		else
			return $this->index( &$tmpl, &$request );
	}
	
	public function login( &$tmpl, &$request )
	{
		global $PREFIX;

		if( isUserLogged() ) return $this->logout( &$tmpl, $request );
		$this->myModel->clearSessionData( );	

		$status=$this->myModel->validateSave( $request );
		if( $status!="" )
		{
			$request['error']=$status;
			$this->index( $tmpl, $request );
			exit( 0 );
		}
		
		$status=$this->myModel->tryLogin( $request );
		if( $status!="" )
		{
			$request['error']=$status;
			$this->index( $tmpl, $request );
			exit( 0 );
		}

		if( isset($request['redirect']) )
			header( "Location: $request[redirect]" );
		else
			header( "Location: $PREFIX" );
	}
}

?>
