<?php

function smarty_function_service_end( $params )
{
	global $DB;

	$date=new DateTime( $DB->GetOne( "SELECT to_date FROM pendings WHERE info=".$DB->qstr($params['adv'])." 
													 AND service_id=".$DB->qstr($params['service']) ) );
	return date_format( $date, "d.m.Y" );
}


?>
