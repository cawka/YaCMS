<?php

class StaticPagesController extends TableController 
{
	public function __construct( &$model, &$helper )
	{
		parent::__construct( $model,$helper,"admin/staticpages_list.tpl",
											"common/static.tpl",
											"common/form.tpl" );
		$this->myCacheLifetime=-1; // cache forever
	}

	public function showCached( &$tmpl, &$request )
	{
		$this->myCachingEnabled=true;
		$this->myCacheId=urlencode( $request[$this->myModel->myId] );
		if( isset($request['print']) ) $this->myCacheId.="|print";

		return parent::show( $tmpl, $request );
	}

	public function show( &$tmpl, &$request ) //due to some reasons, we could not cache all static pages now
	{
		return parent::show( $tmpl, $request );
	}
}

