<?php

class SiteMapController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","",""
		);
	}
	
	public function index( &$tmpl,&$request )
	{
		$tmpl->caching=true; //force caching
		$tmpl->cache_lifetime=99999999; //we don't need to rebuild this

		return $this->showTemplate( $tmpl, $request, "public/site_map.tpl", "collectDataFetch" );
	}	
}

?>
