<?php

$AMOUNTS=array(
   "4850" => "8",
   "4024" => "12",
   "4042" => "16",
   "4004" => "18",
);

class B2mModel extends BaseModel 
{
	//private $mySecretKey="3Y6W50909J683ablYeTAH1qf3Hea18QYC1W4zx88xeNZ7h1tIc";
	
	public function doPayment( &$request )
	{
		global $DB;
		
		$sms=split(" ", $request['m'] );
		$amount=$this->checkNumberAndGetAmount( $request['s'] );
		if( $amount==0 ) return false;
		$this->myAmount=$amount;
		
		$user=trim( $sms[2] );
		if( !isset($user) || !is_numeric($user) ) return false;
		
		$info=$DB->GetRow( "SELECT * FROM users WHERE user_id=".$DB->qstr($user) );
		if( !$info ) return false;
		
		$debit=$amount;
		$descr="Пополнение лицевого счета через SMS сервис $request[m]";
		
		$DB->Execute( "INSERT INTO balance (user_id,debit,descr,mydate) VALUES
			(".$DB->qstr($user).",".$DB->qstr($debit).",".$DB->qstr($descr).",NOW())" );
		
		return true;
	}
	
	
	private function checkNumberAndGetAmount( $phonenum )
	{
		switch( $phonenum )
		{
			case "4850": return 8;
		   	case "4024": return 12;
		    case "4042": return 16;
		    case "4004": return 18;
		    case "1727": return 1;
		}
		return 0;
	}
}
