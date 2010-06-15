<?php

function smarty_function_ArrayToHidden( $params )
{
	if( !isset($params['array']) || !is_array($params['array']) ) return "null";
	
	$ret="";
	foreach( $params['array'] as $key=>$value )
	{
		$ret.="<input type='hidden' name='comm[$key]' value='$value' />";
	}
	return "$ret";	
}

?>
