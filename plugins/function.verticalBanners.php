<?php

/**
 * SMARTY helper to automatically construct banners block for section
 *
 * @param array $params
 */
function smarty_function_verticalBanners( $params )
{
	$section=$params["section"];
	$catalog=is_numeric($params['cat_id'])?$params['cat_id']:NULL;

	return BannerHelper::displayVerticalBanners( $section, $catalog );
}
