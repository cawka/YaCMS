<?php

if( !defined("CMSDIR") ) define( "CMSDIR",  dirname(__FILE__) );

set_include_path( get_include_path() . PATH_SEPARATOR . CMSDIR );
spl_autoload_register( "cms_autoload" );

include_once( "lib/adodb/adodb-exceptions.inc.php");
include_once( "lib/adodb/adodb.inc.php" );

include_once( "CMS.class.php" );

$theAPC=new APCHelper( false, 300 );
$COOKIES=new CookieHelper( "", 3600*24*365 );

session_start( );

function cms_autoload( $classname )
{
	preg_match( "/^(.+)(column|model|controller|helper)$/i",$classname,$matches );
	$prefix="app/" . strtolower($matches[2]);

	if( is_file("$prefix/$classname.class.php") )
	    include_once( "$prefix/$classname.class.php" );
	else if( is_file("$prefix2/$classname.class.php") )
	    include_once( "$prefix2/$classname.class.php" );
}

