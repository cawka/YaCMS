<?php

class FinancesController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/finances.tpl","","" );
	}
	
	public function user_wallet( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "services/wallet_history.tpl", "getUserWallet" );
	}
}

?>
