<?php

function smarty_function_getPhoto( $params )
{
	return UserPhotoColumn::getPhoto( $params['date'],$params['id'],$params['name'],$params['type'],$params['maincat'] );
}

?>