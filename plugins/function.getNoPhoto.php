<?php

function smarty_function_getNoPhoto( $params )
{
	return UserPhotoColumn::getPhoto( "","","","60x45",$params['tree'] );
}

?>

