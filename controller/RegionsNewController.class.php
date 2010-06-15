<?php

class RegionsNewController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/regions.tpl","","common/form.tpl"
		);
	}

	public function opts( &$tmpl, &$request )
	{
		if( !isset($request['reg_reg_id']) ||
		    !(1<=$request['reg_reg_id'] && $request['reg_reg_id']<=99999) )
		{
			ErrorHelper::get500( );
		}
		print $this->myModel->getInputOptions( $request );
	}
	
	protected function postSave( &$tmpl, &$request )
	{
		global $theAPC;
		$this->myModel->denormalizeLanguages( );

		BaseHelper::clearCache( );
		$theAPC->clear_all( );

		return parent::postSave( $tmpl, $request );
	}
}
