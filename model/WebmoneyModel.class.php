<?php

class WebmoneyModel extends CardModel 
{
	private $mySecretKey="3Y6W50909J683ablYeTAH1qf3Hea18QYC1W4zx88xeNZ7h1tIc";
	private $purse="U562709311610";
    private $purse1="Z226098094000";
    private $purse2="R902553774009";

	public $bank_action="https://merchant.webmoney.ru/lmi/payment.asp";

	private $myDescription;
	
	protected $myTableName="webmoney_transactions";
	protected $myTransField="LMI_PAYMENT_NO";
	
//	public function doCheckPayment( &$request )
//	{
//		global $DB;
//		
//		if( !isset($request['USER_ID']) ) return false;
//		$user=$request['USER_ID'];
//		$ret=$DB->GetOne( "SELECT user_id FROM users WHERE user_id=".$DB->qstr($user) );
//		return $ret;
//	}
//	
//	public function doPayment( &$request )
//	{
//		global $DB;
//		
//		if( !isset($request['USER_ID']) ||
//		    !$this->checkHash($request) ) 
//	    {
//	    	return false;
//	    }
//		
//		$user=$request['USER_ID'];
//		$info=$DB->GetRow( "SELECT * FROM users WHERE user_id=".$DB->qstr($user) );
//		if( !$info ) return false;
//		
//		$debit=$this->checkOrConvertAmount( $request['LMI_PAYMENT_AMOUNT'], 
//										     $request['LMI_PAYEE_PURSE'] );
//		$descr="Пополнение лицевого счета c кошелька WebMoney $request[LMI_PAYER_WM] ($request[LMI_PAYMENT_AMOUNT]). Реквизиты платежа WebMoney: счет номер $request[LMI_SYS_INVS_NO], номер транзакции $request[LMI_SYS_TRANS_NO].";
//		
//		$DB->Execute( "INSERT INTO balance (user_id,debit,descr,mydate) VALUES
//			(".$DB->qstr($user).",".$DB->qstr($debit).",".$DB->qstr($descr).",NOW())" );
//		
//		return true;
//	}

	private function checkOrConvertAmount( $reqested,$payed,$purse )
	{
		global $SETTINGS;
		
		if( !is_numeric($payed) ) return false;
		switch( $purse[0] )
		{
			case 'U': return $reqested==$payed; 
			case 'Z': return abs( $reqested-$payed*(double)$SETTINGS['WMZ_TO_WMU_RATE'] )<0.2;
			case 'R': return abs( $reqested-$payed*(double)$SETTINGS['WMR_TO_WMU_RATE'] )<0.5;
			default: return false;
		}
	}

	public function getHiddenFields()
	{
		global $SETTINGS;
		
		$ret="";
		$ret.="
<script>
function changeWMU(id)
{
    if( id==0 )
        $('value').value='".$this->myEUR."';
    else if( id==1 )
        $('value').value='".round($this->myEUR/(double)str_replace(",",".",$SETTINGS['WMZ_TO_WMU_RATE']),2)."';
    else if( id==2 )
        $('value').value='".round($this->myEUR/(double)$SETTINGS['WMR_TO_WMU_RATE'],2)."';
}
</script>
        
<div class=\"pay_div\" style=\"text-align: center;\">
    <div style='text-align: center;width: 100%'><input type=\"radio\" name=\"LMI_PAYEE_PURSE\" value=\"$this->purse\" onclick=\"changeWMU(0)\" checked=\"checked\" /> WMU</div>
    <div style='text-align: center;width: 100%'><input type=\"radio\" name=\"LMI_PAYEE_PURSE\" value=\"$this->purse1\" onclick=\"changeWMU(1)\" /> WMZ</div>
    <div style='text-align: center;width: 100%'><input type=\"radio\" name=\"LMI_PAYEE_PURSE\" value=\"$this->purse2\" onclick=\"changeWMU(2)\" /> WMR</div>
    </div>";

		$ret.="<input type=\"hidden\" name=\"LMI_PAYMENT_AMOUNT\" id=\"value\" value=\"$this->myEUR\" />\n";		
		$ret.="<input type=\"hidden\" name=\"LMI_PAYMENT_DESC\" value=\"$this->myDescription\" />\n";
		$ret.="<input type=\"hidden\" name=\"LMI_PAYMENT_NO\" value=\"$this->myTransId\" />\n";
		$ret.="<input type=\"hidden\" name=\"LMI_SIM_MODE\" value=\"0\" />\n";
//		$ret.="<input type=\"hidden\" name=\"LMI_RESULT_URL\" value=\"http://reklama.cawka.homeip.net/payment/webmoney/result.html\" />\n";
//		$ret.="<input type=\"hidden\" name=\"LMI_SUCCESS_URL\" value=\"http://reklama.cawka.homeip.net/payment/webmoney/success.html\" />\n";	
//		$ret.="<input type=\"hidden\" name=\"LMI_FAIL_URL\" value=\"http://reklama.cawka.homeip.net/payment/webmoney/fail.html\" />\n";
		$ret.="<input type=\"hidden\" name=\"LMI_RESULT_URL\" value=\"https://www.reklama.com.ua/payment/webmoney/result.html\" />\n";
		$ret.="<input type=\"hidden\" name=\"LMI_SUCCESS_URL\" value=\"https://www.reklama.com.ua/payment/webmoney/success.html\" />\n";	
		$ret.="<input type=\"hidden\" name=\"LMI_FAIL_URL\" value=\"https://www.reklama.com.ua/payment/webmoney/fail.html\" />\n";
		
		$ret.="<input type=\"hidden\" name=\"LMI_SUCCESS_METHOD\" value=\"POST\" />\n";
		return $ret;
	}

	public function getLogo()
	{
		return '
		<div class="pay_div" style="text-align: center;">
		<img src="/images/webmoney.gif" alt="webmoney"></div>
		</div>
		';
	}	
	protected function getTransId( $description )
	{
		global $DB;
//		$this->myDescription=$description;
		
		$ret=$DB->GetOne( "SELECT max(id)+1 FROM webmoney_transactions" );
		if( !$ret ) $ret="1";
		
		return $ret;
	}
	
	protected function getBaseMoney( $money )
	{
		global $SETTINGS;

		return round($money*(1.0-$SETTINGS['discount_WM']),2);
	}

	protected function getConvertedCurrency( )
	{
		global $SETTINGS;
		
		return $this->myUAH;//(int)(100*$this->myUAH/(double)$SETTINGS['EUR_TO_UAH']);
	}
	
	private function validateResult( &$request )
	{
		return $request['LMI_HASH']==strtoupper(md5(
			$request['LMI_PAYEE_PURSE'].
			$request['LMI_PAYMENT_AMOUNT'].
			$request['LMI_PAYMENT_NO'].
			$request['LMI_MODE'].
			$request['LMI_SYS_INVS_NO'].
			$request['LMI_SYS_TRANS_NO'].
			$request['LMI_SYS_TRANS_DATE'].
			$this->mySecretKey.
			$request['LMI_PAYER_PURSE'].
			$request['LMI_PAYER_WM'] ));
	}

	public function getStatus( &$request )
	{
		global $DB;
		
		if( !isset($this->myStatus) ) 
		{
			if( isset($request['LMI_HASH']) )
			{
				$sign_ok=$this->validateResult( $request );
	
				if( $sign_ok ) 
				{
					$this->myStatus=$_POST;
					$this->myStatus['RESULT']="OK";
				}
				else 
					$this->myStatus=array( "RESULT"=>"ERROR" );
			}
			else if( isset($request['LMI_PAYMENT_NO']) )
			{
				$params=$DB->GetRow( "SELECT * FROM $this->myTableName WHERE trans_id=".$DB->qstr($request['LMI_PAYMENT_NO']) );
				if( $params )
				{
					$this->myStatus=array();

					$list=split( "&", $params['params'] );
					foreach( $list as $string )
					{
						$data=split( "=",$string );
						$this->myStatus[$data[0]]=$data[1];
					}
					
					$this->myStatus['RESULT']="OK";
					$this->myStatus['LMI_SYS_TRANS_NO']=$params['LMI_SYS_TRANS_NO'];
					$this->myStatus['LMI_PAYER_PURSE']=$params['LMI_PAYER_PURSE'];
					
					$request['adv']=$this->myStatus['adv'];
					$request['money']=$this->myStatus['money'];
					
					if( !$this->checkOrConvertAmount( $request['money'],$request['LMI_PAYMENT_AMOUNT'],$request['LMI_PAYEE_PURSE']) )
					{
						$this->myStatus=array( "RESULT"=>"ERROR" ); //in case if somebody tries to cheat the system
					}
				}
				else 
					$this->myStatus=array( "RESULT"=>"ERROR" );
			}
		}
		
		return $this->myStatus;
	}
	
	protected function clearTransaction( &$request )
	{
		global $DB;
		
		$DB->Execute( "UPDATE $this->myTableName 
							SET cleared=NOW(),
								LMI_SYS_INVS_NO="   .$DB->qstr($this->myStatus['LMI_SYS_INVS_NO']).",
								LMI_SYS_TRANS_NO="  .$DB->qstr($this->myStatus['LMI_SYS_TRANS_NO']).",
								LMI_SYS_TRANS_DATE=".$DB->qstr($this->myStatus['LMI_SYS_TRANS_DATE']).",
								LMI_PAYER_PURSE="   .$DB->qstr($this->myStatus['LMI_PAYER_PURSE']).",
								LMI_PAYER_WM="      .$DB->qstr($this->myStatus['LMI_PAYER_WM'])."
							WHERE trans_id=".$DB->qstr($request[$this->myTransField]) );
	}

	
	public function refund( &$request )
	{
		$this->getStatus( $request );
		//not supported
	}

	protected function getRefillDescr( )
	{
		$wm=$this->myStatus['LMI_PAYER_WM'];
		$purse=$this->myStatus['LMI_PAYER_PURSE'];
		$invoice=$this->myStatus['LMI_SYS_INVS_NO'];
		return "Пополнение лицевого счета через WebMoney. Номер кошелька $purse (инвойс $invoice)";
	}
}

?>
