<?php

class ErrorHelper
{
	public static function get404( )
	{
		header( $_SERVER["SERVER_PROTOCOL"]." 404 Not Found" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head><title>404 Not Found</title></head>
<body bgcolor=white>
<h1>404 Not Found</h1>

The requested URL <?php print $_SERVER["REQUEST_URI"];?> does not exist.

</body></html>
<?php
    	exit( 0 );
	}

	public static function get403( )
	{
		header( $_SERVER["SERVER_PROTOCOL"]." 403 Forbidden" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head><title>403 Permission Denied</title></head>
<body bgcolor=white>
<h1>403 Permission Denied</h1>

You do not have permission for this request <?php print $_SERVER["REQUEST_URI"];?>

</body></html>
<?php
		exit( 0 );
	}

	public static function get500( )
	{
		header( $_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head><title>403 Permission Denied</title></head>
<body bgcolor=white>
<h1>500 Internal Server Error</h1>

</body></html>
<?php
		exit( 0 );
	}

	public static function redirect( $url="/" )
	{
		header( "Location: $url" );
		exit( 0 );
	}
}

