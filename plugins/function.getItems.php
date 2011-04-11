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

	$request = array( "type" => $params['type'] );
	if( isset($params['sorting']) )
	{
		$request['sort']='msort';
	}

	$smarty->assign( "type", $params['type'] );
	$smarty->caching=false;
	return $controller->index( $smarty, $request ); 
}

