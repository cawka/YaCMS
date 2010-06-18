<?php

class AuthHelper 
{
	private $myRights;
	private $myDefaultRedirect="/";	
	private $myController;
	
	public function __construct( $controller )
	{
		$this->myController=$controller;

		if( $_SESSION['group']=="0" ) return;

		$this->myRights=$this->getRights( $_SESSION['group'] );
		if( !is_array($this->myRights) ) $this->myRights=array();
		
		if( isset($_SESSION['group']) )
		{ //all public rights also granted to logged users
			$this->myRights=array_merge( $this->myRights,
				$this->getRights(NULL) );
		}
	}

	protected function getRights( $group )
	{
		global $RIGHTS;

		return $RIGHTS[$group];
	}
	
	public function canAccessTo( $controller )
	{
		return $this->canUseAction( $controller,'index' );
	}
	
	public function canUseAction( $controller, $action )
	{
		if( $_SESSION['group']=="0" ||
			$controller=="public" || 
			isset($this->myRights[$controller]['all']) ||
			isset($this->myRights[$controller][$action]) )
		{			
			return true;
		}
		else 
			return false;
	}
	
	public function isAllowed( $action )
	{
		return $this->canUseAction( $this->myController, $action );
		return 	$_SESSION[group]=="0" || 
				$this->myRights['all']==1 || 
				$this->myRights[$action]==1;
	}
	
	public function allowOrRedirect( $action )
	{
		if( !$this->isAllowed($action) ) 
		{
			header( "Location: $this->myDefaultRedirect" );
			exit( 0 );
		}
	}
}
