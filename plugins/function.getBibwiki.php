<?php

function smarty_function_getBibwiki( $params,&$smarty )
{
	global $DB, $LANG;

	$controller=
	new BibwikiController(
		new BibwikiModel("bibwiki", $params['biblio_type']),
		new BibwikiHelper()
	);
	$controller->myUseSmartyFetch=true;

	$smarty->caching=false;
	$_REQUEST['biblio_type']=$params['biblio_type'];
	return $controller->index( $smarty, $params ); 
}

