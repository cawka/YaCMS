<?php

function smarty_function_getBibwiki( $params,&$smarty )
{
	global $DB, $LANG;

	$new_smarty = new MySmarty( );

	$controller=
	new BibwikiController(
		new BibwikiModel("bibwiki", $params['biblio_type']),
		new BibwikiHelper()
	);
	$controller->myUseSmartyFetch=true;
	$controller->myAuth = new AuthHelper("bibwiki");
	$controller->myModel->myAuth = $controller->myAuth;
	$new_smarty->assign( "Auth", $controller->myAuth );

	$new_smarty->caching=false;
	$_REQUEST['biblio_type']=$params['biblio_type'];
	return $controller->index( $new_smarty, $params ); 
}

