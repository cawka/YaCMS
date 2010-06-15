<?php

/**
 * SMARTY helper to display banners
 *
 * @param array $params
 * @return banner code
 */
function smarty_function_getBanner( $params )
{
	if( !isset($params['value']) )
		$params=BannerHelper::getBannerFromPool( $params['type'], is_numeric($params['cat_id'])?$params['cat_id']:NULL );
	if( !isset($params) ) return "";

	return BannerHelper::displayBanner( $params['type'],$params['format'],$params['id'],$params['value'],$params['link'] );
}
