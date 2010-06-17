<?php

spl_autoload_register( "cms_autoload" );

require_once( "lib/adodb/adodb-exceptions.inc.php");
require_once( "lib/adodb/adodb.inc.php" );

require_once( "lib/Smarty/Smarty.class.php" );
require_once( "lib/MySmarty.class.php" );

$theAPC=new APCHelper( false, 300 );
$COOKIES=new CookieHelper( "", 3600*24*365 );

session_start( );

function cms_autoload( $classname )
{
	if( preg_match("/^(.+)(column|model|controller|helper)$/i",$classname,$matches) )
	{
		$paths=preg_split( "/:/", get_include_path() );
		$prefix=strtolower($matches[2]);

		$file_exists=false;
		foreach( $paths as $path )
		{
			if( is_file("$path/$prefix/$classname.class.php") ) $file_exists=true;
		}
		if( !$file_exists ) return;

		include_once( "$prefix/$classname.class.php" ); //ignore all errors, if any
	}
}

