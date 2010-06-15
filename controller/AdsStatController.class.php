<?php

class AdsStatController extends BaseController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}
	
	public function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "admin/adsStat.tpl", "prepareData" );
	}
	
	public function xml( &$tmpl, &$request )
	{
		header( 'Content-Type: text/xml' );
		return $this->showTemplate( $tmpl, $request, "admin/adsStat.xml.tpl", "prepareXML" );
	}
}

?>
