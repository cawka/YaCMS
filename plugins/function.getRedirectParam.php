<?php

function smarty_function_getRedirectParam( $param )
{
	return "";//urlencode(urlencode( $_SERVER['PHP_SELF']."?".getRequest(array()) ));
}

?>
