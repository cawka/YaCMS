<?php

class BanCatsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/ban_cats.tpl","","common/form.tpl"
		);
	}	
	
//	protected function postSave( &$tmpl,&$request ) 
//	{
//	}
//	
//	public function save_edit( &$tmpl,&$request )
//	{
//		global $DB;
//		$DB->debug=true;
//
//		parent::save_edit( $tmpl, $request );
//	}
//	
//	public function save_add( &$tmpl,&$request )
//	{
//		global $DB;
//		$DB->debug=true;
//
//		parent::save_add( $tmpl, $request );
//	}
}

?>
