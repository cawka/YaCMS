<?php

class WebmoneyController extends CardController 
{
	protected $baseTemplate="webmoney";
	protected $myTransField="LMI_PAYMENT_NO";
	
//	public function payment( &$tmpl,&$request )
//	{
//		if( isset($request['LMI_PREREQUEST']) && $request['LMI_PREREQUEST']=='1' )
//			$ret=$this->myModel->doCheckPayment( $request );
//		else 
//			$ret=$this->myModel->doPayment( $request );
//
//		print $ret?"YES":"ERROR";
//	}
	
	public function ok( &$tmpl, &$request )
	{
		if( isset($request['LMI_PREREQUEST']) && $request['LMI_PREREQUEST']=='1' )
			return "YES"; //always accept payment check
		else 
			return parent::ok( $tmpl, $request );
	}
	
	protected function retry( &$tmpl, &$request )
	{
		print "ERROR";
	}
	
	protected function siteerror( &$tmpl, &$request )
	{
		print "ERROR";
	}

	protected function receipt( &$tmpl, &$request )
	{
		print "YES";
	}
	
	public function fail( &$tmpl, &$request ) //called from 'ok'
	{
		print "ERROR";
	}
	
	public function success( &$tmpl, &$request ) //called by webmoney
	{		
		$t=$this->myModel->getStatus( $request );
		
		$params=array();
		if( isset($t['type']) ) array_push( $params, "type=$t[type]" );
		if( isset($t['uuid']) ) array_push( $params, "uuid=$t[uuid]" );
		array_push( $params, "services=1" );

		if( sizeof($params)>0 ) $tmpl->assign( "advget", implode("&",$params) );
		
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_receipt.tpl","" );
	}
	
	public function error( &$tmpl, &$request ) //called by webmoney
	{
		return $this->showTemplateDB( $tmpl, $request, "services/".$this->baseTemplate."_error.tpl","getStatus" );
	}
}

?>
