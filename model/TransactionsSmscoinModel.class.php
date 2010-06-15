<?php

class TransactionsSmscoinModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "smscoin_transactions", array(
			), "id",true );
		$this->myOrder="stamp DESC";
		$this->myElementsPerPage=100;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}

	public function getAddCtrl( )
	{
	}

	public function prepareReport( &$request )
	{
		global $DB;

		$from=$DB->qstr( $request['from'] );
		$to=$DB->qstr( $request['to'] );
		$this->myOrder="cleared DESC";

		$this->myExtraWhere=" (cleared IS NOT NULL AND $from<=cleared AND cleared<=$to )";
		return parent::collectData( $request );
	}
}

?>

