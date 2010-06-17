<?php

function smarty_function_getItems( $params,&$smarty )
{
	global $DB, $LANG;

	$controller=
	new ItemsController(
		new ItemsModel("items"),
		new BaseTableThickBoxHelper()
	);
	$controller->myUseSmartyFetch=true;

	$smarty->assign( "type", $params['type'] );
	$smarty->caching=false;
	return $controller->index( $smarty, $params ); 
}

