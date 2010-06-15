<?php

function smarty_function_EncodeArray( $params )
{
	if( !isset($params['array']) || !is_array($params['array']) ) return "null";
	
	$ret="";
	foreach( $params['array'] as $key=>$value )
	{
		if( $ret!="" ) $ret.="&";
		$ret.="comm[$key]=$value";
	}
	return "'$ret'";
}

?>
