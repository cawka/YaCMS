<?php

class MySmarty extends Smarty 
{
	function __construct( )
	{
		global $SETTINGS, $PREFIX;

		parent::__construct( );

		$tmpl_dir=BASEDIR."/view/";
		$subdir="";

        $this->template_dir = $tmpl_dir;
   		$this->compile_dir = TEMPDIR . "/compile";
		$this->cache_dir =   TEMPDIR . "/cache";
    
        $this->caching = false; // caching now handled individually in each controller
		$this->cache_lifetime=300;
        
        array_push( $this->plugins_dir, BASEDIR . "/plugins/" );
        array_push( $this->plugins_dir, CMSDIR . "plugins/" );

        if( !is_dir($this->compile_dir) ) mkdir( $this->compile_dir,0755,true );
        if( !is_dir($this->cache_dir) ) mkdir( $this->cache_dir,0755,true );

		$this->use_sub_dirs=true;
        $this->compile_check=true;
		$this->allow_php_tag=true;
		$this->allow_php_templates=true;

        $this->assign( "SITEURL", SITEURL );
		$this->assign( "SETTINGS", $SETTINGS );
		$this->assign( "PREFIX",   $PREFIX );

		$this->assign( "menu", new MainMenuHelper() );

		$this->register->templateFunction( "isAdmin", "isAdmin" );
		$this->register->templateFunction( "isUserLogged", "isUserLogged" );
		
		if( isUserLogged() )
		{
			$this->assign( "login",   $_SESSION['login'] );
			$this->assign( "u_name",  $_SESSION['fullname'] );
			$this->assign( "u_group", $_SESSION['company'] );
		}
	}
}

