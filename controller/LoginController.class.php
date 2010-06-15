<?php
include_once( "BaseController.class.php" );

class LoginController extends BaseController 
{
	public function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl,$request,"common/login.tpl","get_default_login" );
	}
	
	public function logout( &$tmpl, &$request )
	{
		$this->myModel->clearSessionData( );	
		
		if( isset($request['redirect']) )
		{
			ErrorHelper::redirect( $request["redirect"] );
			exit;
		}
		else
		{
			//return $this->index( &$tmpl, &$request );
			ErrorHelper::redirect( "/" );
		}
	}
	
	public function login( &$tmpl, &$request )
	{
		if( isUserLogged() ) return ErrorHelper::redirect( "/" );
		if( !isset($request['klogin']) ) ErrorHelper::redirect( "/login/" );

//		$status=$this->myModel->validateSave( $request );
//		print $status;
//		die;
//		if( $status!="" )
//		{
//			$request['error']=$status;
//			$this->index( $tmpl, $request );
//			exit( 0 );
//		}
		
		$status=$this->myModel->tryLogin( $request );
		if( $status!="" )
		{
			$request['error']=$status;
			$this->index( $tmpl, $request );
			exit( 0 );
		}

		if( isset($request['redirect']) )
			ErrorHelper::redirect( $request["redirect"] );
		else
		{
			switch( $_SESSION["group"] )
			{
				case 0:  $startpage="adsStat/"; break;
				case 99: $startpage=""; break;
				default: $startpage="search/?user=$_SESSION[user]"; break;
			}
			ErrorHelper::redirect( "/$startpage" );
		}
	}
}
