<?php

class TransactionsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/transactions.tpl","",""
		);
	}

	public function report( &$tmpl, &$request )
	{
		 return $this->showTemplate( $tmpl, $request, "admin/transactions_print.tpl", "prepareReport" );
	}
}

?>
