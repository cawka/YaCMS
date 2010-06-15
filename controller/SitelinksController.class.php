<?php

class SitelinksController extends BaseController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}

	public function index( &$tmpl, &$request )
	{
		header( "Content-type: application/xml; charset=utf-8" );
		$tmpl->caching=true; //force caching
		$tmpl->cache_lifetime=813600; //cache is valid for one hour

		$a=array( "sitelinks" );
		if( isset($request['ads']) )
		{
			array_push( $a, "ads" );
			$page=0;
			if( isset($request['page']) && is_numeric($request['page']) )
			{
				array_push( $a, $request['page'] );
				$page=$request['page'];
			}
			else
				array_push( $a, "0" );
			$this->myModel->collectAds( $request, $page );
		}
		else
		{
			array_push( $a, "cat" );
			$this->myModel->collectCatalog( $request, 0 );
		}

		$this->myCacheId=implode( "|", $a ); 
	    return $this->showTemplate( $tmpl, $request, "public/sitelinks.tpl", "" );
	}
}

?>
