<?php

function smarty_function_form_ctrl( $params,&$smarty )
{
	$model=&$params['model'];
	$data=&$params['data'];
	$action=$params['action']; 
	$validate=$params['validate'];
	
	unset($params['model']);
	unset($params['data']);
	unset($params['action']);
	unset($params['validate']);
	
	return $model->getFormCtrl( $data,$action,$validate,$params );
}

?>
