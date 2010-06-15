<?php

class SmsCoinModel extends CardModel
{
	public $bank_action="http://e6ac.bank.smscoin.com/bank/";
	private $purse="5223";
	private $secret="secret";

	private $myDescription;
	
	protected $myTableName="smscoin_transactions";
	protected $myTransField="s_order_id";
	
	public function getHiddenFields()
	{
		$ret="";
//		return "<input type=\"hidden\" value=\"$this->myTransId\" name=\"trans_id\" />";
		
		$ret.="<input name=\"s_purse\" type=\"hidden\" value=\"$this->purse\" />\n";
		$ret.="<input name=\"s_order_id\" type=\"hidden\" value=\"$this->myTransId\" />\n";
		$ret.="<input name=\"s_amount\" type=\"hidden\" value=\"".($this->myEUR/100.0)."\" />\n";
		$ret.="<input name=\"s_clear_amount\" type=\"hidden\" value=\"0\" />\n";
		$ret.="<input name=\"s_description\" type=\"hidden\" value=\"$this->myDescription\" />\n";
		$ret.="<input name=\"s_sign\" 	type=\"hidden\" value=\"".$this->sign()."\" />\n";	
		
		return $ret;
	}

	public function validate( &$request )
	{
                global $PRICES, $DURATIONS, $DB, $SETTINGS;

                $duration=$request['comm']['duration'];
                $this->myUAH=0;

                $list=array();

                foreach( $request['comm'] as $key=>$value )
                {
                        if( $key=="duration" ) continue;
                        $service=$this->types[$key];
                        $prices=split( ",",$PRICES[$service] );

                        $this->myUAH+=$prices[$duration];

                        array_push( $list,$service );
                }
		
		return $this->myUAH <= 50;
	}

	public function getLogo()
	{
		return '
		<div class="pay_div" style="text-align: center;">
		</div>
		';
//		 <!--				<img src="../images/webmoney.gif" alt="webmoney">--></div>
	}	
	protected function getTransId( $description )
	{
		global $DB;
//		$this->myDescription=$description;
		
		$ret=$DB->GetOne( "SELECT max(id)+1 FROM smscoin_transactions" );
		if( !$ret ) $ret="1";
		
		return $ret;
	}

	protected function getBaseMoney( $money )
	{
		global $SETTINGS;

		return $money;
	}

	protected function getConvertedCurrency( )
	{
		global $SETTINGS;
		
		return (int)(100*$this->myUAH/(double)$SETTINGS['EUR_TO_UAH']);
	}
	
	private function sign( )
	{
		$string="$this->purse::$this->myTransId::".($this->myEUR/100.0)."::0::$this->myDescription::$this->secret";
		return md5( $string );
	}
	
	private function validateResult( &$request )
	{
		$string="$this->secret::$this->purse::$request[s_order_id]::$request[s_amount]::$request[s_clear_amount]::$request[s_inv]::$request[s_phone]";
		
		return md5($string)==$request['s_sign_v2'];
	}
	
	private function validateStatus( &$request )
	{
		return true;
	}
	
	public function getStatus( &$request )
	{
		global $DB;
		
		if( !isset($this->myStatus) ) 
		{
			$sign_ok=false;
			if( isset($request['s_sign_v2']) ) //result page
			{
				$sign_ok=$this->validateResult( $request );
			}
			else if( isset($request['s_sign']) ) //status page
			{
				$sign_ok=$this->validateStatus( $request );
			}
			
			if( $sign_ok ) 
			{
				$this->myStatus=$_POST;
				$params=$DB->GetRow( "SELECT * FROM $this->myTableName WHERE trans_id=".$DB->qstr($request[$this->myTransField]) );
				
				$list=split( "&", $params['params'] );
				foreach( $list as $string )
				{
					$data=split( "=",$string );
					$this->myStatus[$data[0]]=$data[1];
				}
				
				$this->myStatus['s_amount']=$params['s_amount'];
				$this->myStatus['s_clear_amount']=$params['s_clear_amount'];
				$this->myStatus['phone']=$params['phone'];
				$this->myStatus['inv']=$params['inv'];
				
				$request['adv']=$this->myStatus['adv'];
				$request['money']=$this->myStatus['money'];
				
				if( !isset($request['s_status']) ) 
				{
					$this->myStatus["RESULT"]="OK";
				}
				else 
					$this->myStatus["RESULT"]=($request['s_status']==1)?"OK":"ERROR";
					
				return $this->myStatus;
			}
			else 
				$this->myStatus=array( "RESULT"=>"ERROR" );
		}
	}
	
	protected function clearTransaction( &$request )
	{
		global $DB;
		
		$DB->Execute( "UPDATE $this->myTableName 
							SET cleared=NOW(),
								phone=".$DB->qstr($this->myStatus['s_phone']).",
								inv=".$DB->qstr($this->myStatus['s_inv'])."
							WHERE trans_id=".$DB->qstr($request[$this->myTransField])." " );
	}

//								s_amount=".$DB->qstr($this->myStatus['s_amount']).",
//								s_clear_amount=".$DB->qstr($this->myStatus['s_clear_amount'])."
	
	public function refund( &$request )
	{
		$this->getStatus( $request );
		//not supported
	}

	protected function getRefillDescr( )
	{
		$phone=$this->myStatus['s_phone'];
		$invoice=$this->myStatus['s_inv'];
		return "Пополнение лицевого счета c мобильного телефона $phone (инвойс $invoice)";
	}
}

?>
