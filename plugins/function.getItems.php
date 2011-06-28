<?php

function smarty_function_getItems( $params,&$smarty )
{
	global $DB, $LANG;

	$new_smarty = new MySmarty( );

	$controller=
	new ItemsController(
		new ItemsModel("items"),
		new BaseTableThickBoxHelper()
	);
	$controller->myAuth = new AuthHelper("items");
	$controller->myModel->myAuth = $controller->myAuth;
	$controller->myUseSmartyFetch=true;
	$new_smarty->assign( "Auth", $controller->myAuth );

	$request = array( "type" => $params['type'] );
	if( isset($params['sorting']) )
	{
		$request['sort']='msort';
	}

	$new_smarty->assign( "type", $params['type'] );
	$new_smarty->caching=false;
	return $controller->index( $new_smarty, $request ); 
}

