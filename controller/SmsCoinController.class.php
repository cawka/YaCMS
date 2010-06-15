<?php

class SmsCoinController extends CardController 
{
	protected $baseTemplate="smscoin";
	protected $myTransField="s_order_id";
	
	protected function retry( $tmpl, $request )
	{
		return $this->fail( $tmpl, $request );
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
	
	protected function siteerror( $tmpl, $request )
	{
		return $this->fail( $tmpl, $request );
	}
}

?>
