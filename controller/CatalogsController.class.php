<?php

class CatalogsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"","","common/form.tpl"
		);
	}

	public function index( &$tmpl, &$request )
	{ // our index page
		BaseHelper::disablePOST( );

		$this->myCachingEnabled=true;
		$this->myCacheLifetime=604800;

		$this->myModel->setCatId( NULL );
		return $this->showTemplate( $tmpl, $request, "catalogs/index.tpl", "collectData" );
	}

	public function show( &$tmpl, &$request )
	{ // our catalog pages
		BaseHelper::disablePOST( );

		if( !isset($request['id']) ) ErrorHelper::redirect( "/" );
		$this->myModel->setCatId( $request['id'] );

		$this->myCachingEnabled=true;
		$this->myCacheLifetime=604800;
		$this->myCacheId=$request['id'];

		return $this->showTemplate( $tmpl, $request, "catalogs/".$this->myModel->myInfo['cat_template'],"collectData" );
	}

	public function opts( &$tmpl, &$request )
	{
		if( !isset($request['cat_cat_id']) ||
		    !(1<=$request['cat_cat_id'] && $request['cat_cat_id']<=99999) )
		{
			ErrorHelper::get500( );
		}
		print $this->myModel->getInputOptions( $request );
	}

	protected function postSave( &$tmpl, &$request )
	{
		$this->myModel->DenormalizeAfterSave( );
		$this->myHelper->clearCache( );
		return parent::postSave( $tmpl, $request );
	}

	
}
