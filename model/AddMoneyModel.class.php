<?php

class AddMoneyModel extends BaseModel 
{
	public $myColumns;
	
	public function __construct( $php )
	{
		parent::__construct( $php );
		$this->myColumns=array( 
			new ListDBColumn( "user_id","Логин пользователя", "Выберите пользователя","users","user_id","u_login" ),
			new IntegerColumn( "money","Количество денег"),
			new TextColumn( "comment","Комментарий к операции"),
		);	
	}
	
	public function getUsers( &$request )
	{
		global $DB;
		
//		$this->myUsers=$DB->GetAssoc( "SELECT user_id,u_login FROM users ORDER BY u_login" );
//		$this->myUsers=$res->GetRows();
		$this->myData=&$request;
	}
	
	public function addMoney( &$request )
	{
		global $DB;
		
		$user_id=$request['user_id'];
		$money=$request['money'];
		$comment=$request['comment'];
		
		if( !is_numeric($money) ) return false;
		
		$DB->Execute( "INSERT INTO balance (user_id,debit,descr,mydate) ".
						  "VALUES('$user_id','$money',".
						  $DB->qstr("$comment").",NOW())" );
		return true;
	}
	
	public function isId() { return false; }
}

?>