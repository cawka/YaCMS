<?php

class BannersNewController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/banners.tpl","","common/form.tpl"
		);
	}
	
	public function click( &$tmpl,&$request )
	{
		$link=$this->myModel->getBannerLink( $request );
		header( "Location: ".$link );
	}
}

?>
