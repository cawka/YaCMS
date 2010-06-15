<?php

function smarty_function_sape_get_links( $params, &$smarty )
{
	global $SAPE;
	$count=$params["count"];
	
//	if( isset($count) )
//		return $SAPE->return_links( $count );
//	else 
//	return str_replace( "class=\"sape\"", "class=\"links\"", $SAPE->return_links( ) );
	return $SAPE->return_links( );
}

?>
