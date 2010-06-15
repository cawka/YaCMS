<?php

/**
 * MyBanners controller
 *
 * !!! ATTENTION. Only 'index' action should be enabled
 */
class UserBannersController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"services/user_banners.tpl","",""
		);
	}
	
	public function stat( &$tmpl, &$request )
	{
		$template="services/user_banners_stat.tpl";
		if( isset($request['_f']) && $request['_f']=='xml' ) 
		{
			header( "Content-type: text/xml; charset=utf-8" );
			$template.=".xml";
		}
	
		return $this->showTemplate( $tmpl, $request, $template, "getStat" );
	}
}

?>
