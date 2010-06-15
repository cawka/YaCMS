<?php

class CardModel extends CommercialServicesModel
{
	public $bank_action="https://acsx1.nordlb.lv/ecomm/ClientHandler";
	
	protected $myTableName="card_transactions";
	protected $myTransField="trans_id";
	
	protected function getTransId( $description )
	{
		return ECommerce::startSMSTrans( $this->myEUR."", $description );
	}

	protected function getBaseMoney( $money )
	{
		global $SETTINGS;

		return round($money*(1.0-$SETTINGS['discount_CREDIT']),2);
	}

	protected function getConvertedCurrency( )
	{
		global $SETTINGS;
		
		$ret=(int)(100*$this->myUAH/(double)$SETTINGS['EUR_TO_UAH']);
		$ret=(int)((1.0-$SETTINGS['discount_CREDIT'])*$ret);
		return $ret;
	}

	public function validate( )
	{
		return true;
	}
	
	public function getHiddenFields()
	{
		return "<input type=\"hidden\" value=\"$this->myTransId\" name=\"trans_id\" />";
	}
	
	public function getLogo()
	{
		return '
		<div class="pay_div" style="text-align: center;">
		<img src="../images/mastercard.gif" alt="mastercard" />
		<img src="../images/maestro.gif" alt="maestro" /> 
		<img src="../images/visa.gif" alt="visa" /> 
		<img src="../images/visa_electron.gif" alt="visa electron" />
		</div>
		';
//		 <!--				<img src="../images/webmoney.gif" alt="webmoney">--></div>
	}

	public function prepareRefill( &$request )
	{
		global $langdata, $SETTINGS, $DB;
		
		if( $request['money']<1 )
		{
			$request['money']=1;
			$this->myError=$langdata['MinimumRefillByCard'];//."Минимальная сумма пополнения лицевого счета составляет $5";
		}
		
		$this->myUAH=$this->getBaseMoney( $request['money'] );
		$this->myEUR=$this->getConvertedCurrency( );
		
		$this->myTransId=$this->getTransId( "ACCOUNT $_SESSION[user] REFILL" );
		
		if( $this->myTransId=="ERROR" )
		{
			header( "Location: /merchant_error.html" );
			exit( 0 );
		}

		$params="money=$this->myUAH&user_id=$_SESSION[user]";
		
		$DB->Execute( "INSERT INTO $this->myTableName (stamp,trans_id,params,ip,sum_eur,sum_uah) VALUES(NOW(),".
						$DB->qstr($this->myTransId).",".
						$DB->qstr($params).",".
						$DB->qstr($_SERVER['REMOTE_ADDR']).",".
						$DB->qstr($this->myEUR).",".
						$DB->qstr($this->myUAH).
						")" );
	}
	
	public function prepareInstantBuy( &$request )
	{
		global $PRICES, $DURATIONS, $DB, $SETTINGS;
		
		$duration=$request['comm']['duration'];
		$this->myUAH=0;
		
		$list=array();
		
		foreach( $request['comm'] as $key=>$value )
		{
			if( $key=="duration" ) continue;
			$service=$this->types[$key];
			$prices=preg_split( "/,/",$PRICES[$service] );
			
			$this->myUAH+=$prices[$duration];
			
			array_push( $list,$service );
		}

		#if( $this->myUAH ) 
		
		$tmp=preg_split( "/,/",$DURATIONS[2] );
		$this->myDuration=$tmp[$duration];

// Maybe will be uncommented and extended in the future		
//		if( isset($_SESSION['user']) )
//		{
//			$this->myUserInfo=$DB->GetRow( "SELECT * FROM users WHERE user_id='$_SESSION[user]'" );
//			$this->myUserBalance=$this->myUserInfo['balance'];
//			$diff=$this->myUserBalance-$this->myUAH;
//			if( $diff>=0 )
//			{
//				$params=array( "money"=>$this->myUAH,
//				 			   "duration"=>$duration,
//				 			   "services"=>implode(",",$list),
//				 			   "adv"=>$request[adv],
//				 			   "uuid"=>$request[uuid] );
//				$this->buyServices( $params );
//				header( "Location: /show-$request[adv].html?uuid=$request[uuid]&type=new" );
//				exit( 0 );
//			}
//		}

		$this->myUAH=$this->getBaseMoney( $this->myUAH );
		$this->myEUR=$this->getConvertedCurrency( );
		$this->myTransId=$this->getTransId( "Instant Services Adv$request[adv]" );
		
		if( $this->myTransId=="ERROR" )
		{
			header( "Location: /merchant_error.html" );
			exit( 0 );
		}
		
		$params="money=$this->myUAH&duration=$duration&services=".implode(",",$list);
		if( isset($request['adv'])  ) $params.="&adv=$request[adv]";
		if( isset($request['uuid']) ) $params.="&uuid=$request[uuid]";
		if( isset($request['type']) ) $params.="&type=$request[type]";
		if( isset($request['user_id']) ) $params.="&user_id=$request[user_id]";

		$DB->Execute( "INSERT INTO $this->myTableName (stamp,trans_id,params,ip,sum_eur,sum_uah) VALUES(NOW(),".
						$DB->qstr($this->myTransId).",".
						$DB->qstr($params).",".
						$DB->qstr($_SERVER['REMOTE_ADDR']).",".
						$DB->qstr($this->myEUR).",".
						$DB->qstr($this->myUAH).
						")" );
	}
	
	public function getStatus( &$request )
	{
		if( !isset($this->myStatus) )
		       $this->myStatus=ECommerce::getTransResult( $request['trans_id'] );	
	}
	
	protected function clearTransaction( &$request )
	{
		global $DB;
		
		$DB->Execute( "UPDATE $this->myTableName 
							SET cleared=NOW(),
								result_code=".$DB->qstr($this->myStatus['RESULT_CODE']).",
								rrn=".$DB->qstr($this->myStatus['RRN']).",
								app_code=".$DB->qstr($this->myStatus['APPROVAL_CODE']).
							" WHERE trans_id=".$DB->qstr($request[$this->myTransField])." " );
	}
	
	public function getType( &$request )
	{
		global $DB;
		$params=$DB->GetOne( "SELECT params FROM $this->myTableName WHERE ".
							" cleared IS NULL AND ". 
							" trans_id=".$DB->qstr($request[$this->myTransField]) );
		if( !$params ) return "retry";
		
		$this->clearTransaction( $request );
		
		if( $this->myStatus['RESULT']!="OK" ) return "declined";

		$list=preg_split( "/&/", $params );
		foreach( $list as $string )
		{
			$data=preg_split( "/=/",$string );
			$request[$data[0]]=$data[1];
		}
		
		if( isset($request['adv']) )
			return "instant";
		else if( isset($request['user_id']) )
			return "refill";
	}
	
	public function refund( &$request )
	{
		$this->getStatus( $request );
		ECommerce::reverse( $request[$this->myTransField], $request['money']."00" );
	}

	protected function getRefillDescr( )
	{
		$card_number=$this->myStatus['CARD_NUMBER'];
		$approval_code=$this->myStatus['APPROVAL_CODE'];
		return "Пополнение лицевого счета c кредитной карты $card_number (Код авторизации $approval_code)";
	}	
}

?>
