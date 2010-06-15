<?php

function smarty_function_ip2country( $params, &$smarty )
{
	global $DB;

	$ip=$DB->qstr( $params['ip'] );
	return $DB->GetOne( "SELECT (CASE WHEN name IS NOT NULL THEN name ELSE ip_country END) as n FROM ip_addresses ip
			LEFT JOIN countries c on c.code=ip_country  WHERE $ip <<= ip_address" );
}

?>
