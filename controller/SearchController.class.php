<?php

class SearchController extends BaseController
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}

	public function index( &$tmpl, &$request )
	{
		$template="ads/list_search.tpl";
		if( isset($request['rss']) ) 
		{
			header( "Content-type: application/rss+xml; charset=utf-8" );
			$template.=".rss.xml";
		}

		return $this->showTemplate( $tmpl, $request, $template, "collectData" );
	}

	public function advanced( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "common/search_form.tpl", "prepareAdvanced" );
	}
}

?>
