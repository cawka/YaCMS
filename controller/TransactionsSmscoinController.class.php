<?php

class TransactionsSmscoinController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/transactionsSmscoin.tpl","",""
		);
	}

	public function report( &$tmpl, &$request )
	{
		 return $this->showTemplate( $tmpl, $request, "admin/transactionsSmscoin_print.tpl", "prepareReport" );
	}
}

?>
