<?php

class TransactionsWebmoneyController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/transactionsWebmoney.tpl","",""
		);
	}

	public function report( &$tmpl, &$request )
	{
		 return $this->showTemplate( $tmpl, $request, "admin/transactionsWebmoney_print.tpl", "prepareReport" );
	}
}

?>
