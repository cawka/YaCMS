<?php

spl_autoload_register( "cms_autoload" );

require_once( "lib/adodb/adodb-exceptions.inc.php");
require_once( "lib/adodb/adodb.inc.php" );

require_once( "lib/Smarty/Smarty.class.php" );
require_once( "lib/MySmarty.class.php" );

$theAPC=new APCHelper( true, 300 );
$COOKIES=new CookieHelper( "", 3600*24*365 );

session_start( );

function cms_autoload( $classname )
{
	if( preg_match("/^(.+)(column|model|controller|helper)$/i",$classname,$matches) )
	{
		$paths=preg_split( "/:/", get_include_path() );

		foreach( $paths as $path )
		{
			//print "$path/".strtolower($matches[2])."/$classname.class.php<br/>";
			if( is_file("$path/".strtolower($matches[2])."/$classname.class.php") )
			{
				include_once( strtolower($matches[2])."/$classname.class.php" ); //ignore all errors, if any
				break;
			}
		}
	}
}
