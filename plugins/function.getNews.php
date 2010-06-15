<?php

function smarty_function_getNews( $params,&$smarty )
{
	global $DB, $LANG;

	$data=$DB->Execute( "SELECT n.id,publ_begin,publ_end,top,image,t1.t_text as subject,t2.t_text as body
	       	FROM news n
		JOIN texts t1 ON t1.t_lang_id=$LANG and t1.t_id=n.subject
		JOIN texts t2 ON t2.t_lang_id=$LANG and t2.t_id=n.body
		
		WHERE publ_begin<=NOW() and NOW()<=publ_end ORDER BY publ_begin DESC" );
	$smarty->assign( "data", $data );

	return $smarty->fetch( "blocks/newsTop.tpl" );
}

?>
