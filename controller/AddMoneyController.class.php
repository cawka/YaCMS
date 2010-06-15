<?php

class AddMoneyController extends BaseController 
{
	public function index( &$tmpl,&$request )
	{
		return $this->showTemplate( $tmpl,$request,"admin/add_money.tpl","getUsers" );
	}
	
	public function addMoney( &$tmpl,&$request )
	{
		$ret=$this->myModel->addMoney( $request );
		if( $ret )
			$msg="Деньги добавлены";
		else 
		{
			$msg="Ошибка добавления денег на счет пользователя";
			$tmpl->assign( "user_id", $request["user_id"] );
			$tmpl->assign( "money",   $request["money"] );
		}
		$tmpl->assign( "error", $msg );
		return $this->index( $tmpl,$request );
	}
}

?>