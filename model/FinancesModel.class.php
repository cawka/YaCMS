<?php

class FinancesModel extends TableModel
{
	public $myTitle="История счетов";
	
	public function __construct( $php )
	{
		global $DB;

		parent::__construct( $DB,$php,"(SELECT b.*,u.u_login,u.balance
			FROM balance b
			LEFT JOIN users u ON b.user_id=u.user_id) balance",array() );
		$this->myOrder="mydate DESC";
		$this->myElementsPerPage=100;
		$this->myIsOffset=true;
		
		$this->mySearchColumns=array(
			array( "column"=>new BooleanColumn("plus","Только пополнение баланса"), "type"=>"custom", "where"=>"debit>0" ),
			array( "column"=>new BooleanColumn("minus","Только покупка услуг"), "type"=>"custom", "where"=>"credit>0" ),
			array( "column"=>new TextColumn("u_login","Логин пользователя"), "type"=>"like"),
		);
	}
	
	public function getUserWallet( &$request )
	{
		$userid=$_SESSION['user'];
		$this->myElementsPerPage=30;
		$this->mySearchColumns=NULL;
		$this->myTableName="(SELECT b.*,u.u_login,u.balance FROM balance b JOIN users u ON b.user_id=u.user_id WHERE u.user_id='$userid') balance";
		
		return parent::collectData( $request );
	}
}

?>
