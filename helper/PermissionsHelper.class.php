<?php

class PermissionsHelper
{
}

function isAdmin( $admingroup="0" )
{
	global $_SESSION;
	
	if( isUserLogged() && ($_SESSION["group"]=="0" || $_SESSION["group"]==$admingroup) ) return true;
	return false;
}

function allowAccessOnlyToAdmin( $admin="0" )
{
	if( !isAdmin($admin) ) { header( "Location: /"); exit( 0 ); }
}

function isUserLogged( )
{
	global $_SESSION;
//	print_r( $_SESSION );
	
	if( isset($_SESSION['user']) ) 
	{
		return true;
//		if( $_SESSION["company"]=="-anon-" )
//			return "anon";
//		else
//			return "legal";
	}
	else 
	{
		return false;
	}
}

function isPermission( $razdel )
{
	switch( $razdel )
	{
		case "settings":
		case "templates":
		case "users":
			if( isAdmin() ) return true;
			break;
		case "client":
			if( isUserLogged() && !isAdmin() ) return true;
			break;
	}
	
	return false;
}

?>
