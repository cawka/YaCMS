<?php

class CommercialServicesController extends BaseController 
{
	public function buy( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl,$request,"services/confirm_payment.tpl","getServiceInfo",false );
	}
	
	public function confirm( &$tmpl, &$request )
	{
		$status=$this->myModel->save( $request );
		if( $status!="" )
		{
			$request['error']=$status;
			$this->buy( $tmpl, $request );
			exit( 0 );
		}
		
		$redirect=$request['redirect'];
		if( $redirect=="" ) $redirect="/";
		header( "Location: $redirect" );
	}
}

?>
