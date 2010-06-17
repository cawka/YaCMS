<?php

function smarty_function_getBibwiki( $params,&$smarty )
{
	global $DB, $LANG;

	$controller=
	new BibwikiController(
		new BibwikiModel("bibwiki"),
		new BibwikiHelper()
	);
	$controller->myUseSmartyFetch=true;

//	$smarty->assign( "biblio_type", $params['type'] );
	$smarty->caching=false;
	return $controller->index( $smarty, $params ); 
}

