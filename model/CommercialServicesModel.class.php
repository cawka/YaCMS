<?php

class CommercialServicesModel extends BaseModel 
{
	public $myBalance;
	public $myServiceInfo;
	
	public $myTitle="";
	public $myMin=5;
	
	public $types=array( "bold"=>"2",
					     "up"  =>"3",
					     "top" =>"5" ,
					     "logo"=>"1",
						 "add" =>"6" );
	
	public function __construct( $php )
	{
		global $langdata;
		parent::__construct( $php );
		
		$this->myBalance=new AccountColumn("balance",$langdata['balans_scheta']);
		$this->myServiceInfo=array(
			"name"    =>new StaticTextColumn("name",$langdata['you_have_ordered'],true,NULL,false,"",true ),
/*			"duration"=>new TextColumn("duration","Срок действия услуги",NULL,false,"",NULL,0,"","дней" ),
			"price"   =>new TextColumn("price","Стоимость",NULL,false,"",NULL,0,"","грн." ),*/
		);
		$this->myService=$_REQUEST['id'];
	}
	
	public function getServiceInfo( &$request )
	{
		global $DB,$PRICES,$DURATIONS;
		$service=$request['id'];
		if( !isset($service) ) return;
		
		$this->myData=APC_GetRow( array("services",$service),$DB,
                "SELECT * FROM services WHERE id='$service'",0 );
		if( isset($_SESSION[user]) ) $this->myUserInfo=$DB->GetRow( "SELECT * FROM users WHERE user_id='$_SESSION[user]'" );

		$this->myDurations=split( ",",$DURATIONS[$service] );
		$this->myPrices=split( ",",$PRICES[$service] );
	}
	
	public function save( &$request )
	{
		global $DB,$langdata,$PRICES,$DURATIONS;
		
		$service=$request['service'];
		if( !isset($service) ) return "Системная ошибка1";
		
		$this->myUserInfo=$DB->GetRow( "SELECT * FROM users WHERE user_id='$_SESSION[user]'" );
		
		$DB->BeginTrans( );
		$ret=$this->addService( $service, $request['duration'], $this->myUserInfo['balance'], $_SESSION['user'],$request['adv'] );
		if( $ret!="" )
		{
			$DB->CommitTrans( false );
			return $ret;
		}
		$DB->CommitTrans( true );
		return "";
	}
	
	public function addService( $service, $duration_i, $balance, $user, $adv )
	{
		global $DB,$langdata;
		
		$request['id']=$service;
		$this->getServiceInfo( $request );
		if( !$this->myData ) return "Системная ошибка2";
		
        $price=$this->myPrices[$duration_i];
        if( !isset($price) ) return "Системная ошибка3";
	
//	if( $balance<$price ) return $langdata['not_enough_money'];
	$duration=$this->myDurations[$duration_i];
		
		switch( $service )
		{
		case 1: //LOGO
			if( $user=="" ) return "Сервис недоступен для анонимных пользователей";
			
			$this->processPendings( $service,$user,$price,$duration,"",$user );
			$DB->Execute( "UPDATE users SET is_logo_allowed='t' WHERE user_id='$user'" );
			break;
		case 2: //MAKE BOLD
			if( !isset($adv) ) return "Системная ошибка4";

			$this->processPendings( $service,$adv,$price,$duration," для объявления №$adv",$user );
			$DB->Execute( "UPDATE data SET comm_bold='t' WHERE id='$adv'" );
			break;
		case 3: //SHOW FIRST
			if( !isset($adv) ) return "Системная ошибка5";

			$this->processPendings( $service,$adv,$price,$duration," для объявления №$adv",$user );
			$DB->Execute( "UPDATE data SET comm_up='t' WHERE id='$adv'" );
			break;
		case 5: //PLACE ON TOP PAGE
			if( !isset($adv) ) return "Системная ошибка6";
			$photo=$DB->GetOne( "SELECT brief_photo FROM data WHERE id='$adv'" );
//			if( $photo=="" ) return $langdata["top_allowed_only_with_photo"];

			$this->processPendings( $service,$adv,$price,$duration," для объявления №$adv",$user );
			$DB->Execute( "UPDATE data SET comm_top='t' WHERE id='$adv'" );
			break;
		case 6: //MAKE HIDDEN ADVERTISEMENT VISIBLE
			if( !isset($adv) ) return "Системная ошибка7";
			
			$this->processPendings( $service,$adv,$price,$duration," для объявления №$adv",$user );
			$DB->Execute( "UPDATE data SET flag_category=NULL,comm_add='t' WHERE id='$adv'" );
			break;
		}
		$DB->CommitTrans( true );
		
		return "";
	}
	
	public function buyServices( &$request )
	{
		global $DB,$PRICES;
		
		$request['user_id']=$_SESSION['user'];
		$this->refillAcount( $request );
		$user=$_SESSION['user'];
		if( !isset($_SESSION['user']) ) $user="";
		
		$balance=$request['money'];
		$DB->BeginTrans( );

		$list=split( ",",$request['services'] );
	
		foreach( $list as $service )
		{
//			print "$service,$request[duration],
//				                $balance,$user,$request[adv]<br/>";
			$ok=$this->addService( $service,$request['duration'],
					           $balance,$user,$request['adv'] );
			if( $ok!="" ) continue;
//			{
//				$DB->CommitTrans( false );
//				print "FALSE"; die;
//				return false;
//			}
			
			$balance-=$this->myPrices[ $request['duration'] ];
		}
		$DB->CommitTrans( true );
//		die;
		return true;
	}
	
	
	public function refillAcount( &$request )
	{
		global $DB;

		if( isset($request['user_id']) )
		{
			$user=$DB->GetOne( "SELECT user_id FROM users WHERE user_id=".$DB->qstr($request['user_id']) );
			if( !$user ) return false;
		}
		
		$debit=$request['money'];
		$descr=$this->getRefillDescr( );
		
		$DB->Execute( "INSERT INTO balance (user_id,debit,descr,mydate) VALUES
			(".(isset($user)?$DB->qstr($user):"NULL").",".$DB->qstr($debit).",".$DB->qstr($descr).",NOW())" );
		return true;
	}
	
	protected function getRefillDescr( )
	{
		return "Пополнение лицевого счета";
	}

//////////////////////////////////////////////////////////////////
// private:	
	private function addPending( $service,$info,$duration )
	{
		global $DB,$langdata;
		$DB->Execute( "INSERT INTO pendings (service_id,info,from_date,to_date,is_active)
					VALUES('$service','$info',NOW(),NOW()+'$duration days'::interval,'t')" );
	}
	
	private function extendPending( $id,$service,$info,$duration )
	{
		global $DB,$langdata;
		$DB->Execute( "UPDATE pendings SET to_date=to_date+'$duration days'::interval 
							  WHERE id='$id'" );
	}
	
	private function processPendings( $service,$info,$price,$duration,$comment="",$user )
	{
		global $DB,$langdata;
		$DB->Execute( "INSERT INTO balance (user_id,credit,descr,mydate) ".
						  "VALUES(".($user!=""?"'$user'":'NULL').",'".$price."',".
						  $DB->qstr("Покупка услуги \"".$langdata[$this->myData['name']]."\" ".
						  "на ".$duration." дней$comment").",NOW())" );
		
		
		$id=$DB->GetOne( "SELECT id FROM pendings 
								WHERE service_id='$service' AND 
									  from_date<=NOW() AND NOW()<=to_date AND 
									  info='$info'" );
		if( $id )
			$this->extendPending( $id,$service,$info,$duration );
		else
			$this->addPending( $service,$info,$duration );
	}
}

?>
