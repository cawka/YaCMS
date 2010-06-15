<?php

class CardController extends BaseController 
{
	protected $baseTemplate="card";
	protected $myTransField="trans_id";
	
	public function start( &$tmpl, &$request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/refill_by_card.tpl", "prepareRefill" );
	}
	
	public function instantbuy( &$tmpl, &$request )
	{
		if( !$this->myModel->validate($request) ||
		    !isset($request['comm']) || !is_array($request['comm']) || sizeof($request['comm'])<=1 )
		{
			$params=array();
			array_push( $params, "adv=$request[adv]" );
			if( isset($request['type']) ) array_push( $params, "type=$request[type]" );
			if( isset($request['uuid']) ) array_push( $params, "uuid=$request[uuid]" );

			if( isset($_SERVER['HTTP_REFERER']) )
				header( "Location: ".$_SERVER['HTTP_REFERER'] );
			else
				header( "Location: /" );
			exit( 0 );
		}
		return $this->showTemplateDB( $tmpl, $request, "services/instant_buy.tpl", "prepareInstantBuy" );
	}

	public function logo( &$tmpl, &$request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/instant_buy_logo.tpl", "prepareInstantBuy" );
	}
		
	public function ok( &$tmpl, &$request )
	{
//		$file=fopen( "/tmp/test.txt", "a" );
//		fprintf( $file, "%s\n\n", print_r($request,true) );
//		fprintf( $file, "%s\n", $this->myTransField );
//		fclose( $file );
//		
		$this->myModel->getStatus( $request );
		if( isset($request[$this->myTransField]) )
		{
			$type=$this->myModel->getType( $request );
			switch( $type )
			{
				case 'refill':
					$ok=$this->myModel->refillAcount( $request );
					$tmpl->assign( "money", $request['money'] );
					if( !$ok ) 
					{
						$this->myModel->refund( $request );
						return $this->siteerror( $tmpl, $request );
					}
					else
					{
						$this->receipt( $tmpl, $request );
					}
					break;
				case 'instant':
					$ok=$this->myModel->buyServices( $request );
					$params=array();
					$tmpl->assign( "adv", $request['adv'] );
					$tmpl->assign( "money", $request['money'] );
					
					if( isset($request['type']) ) array_push( $params, "type=$request[type]" );
					if( isset($request['uuid']) ) array_push( $params, "uuid=$request[uuid]" );
					array_push( $params, "services=1" );

					if( sizeof($params)>0 ) $tmpl->assign( "advget", implode("&",$params) );

					if( !$ok ) 
					{
						$this->myModel->refund( $request );
						
						return $this->siteerror( $tmpl, $request );
					}
					else
						return $this->receipt( $tmpl, $request );
					break;
					
				case 'retry':
					return $this->retry( $tmpl, $request );
					break;
				case 'declined':
					return $this->fail( $tmpl, $request );
					break;				
				default:
					return $this->fail( $tmpl, $request );
			}
		}
		else
		{
			return $this->fail( $tmpl, $request );
		}
	}
	
	protected function retry( $tmpl, $request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_error_retry.tpl","" );
	}
	
	protected function siteerror( $tmpl, $request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_siteerror.tpl","" );
	}

	protected function receipt( $tmpl, $request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_receipt.tpl","" );
	}
	
	public function fail( &$tmpl, &$request )
	{
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_error.tpl","getStatus" );
	}
}

?>
