<?php

class SendMailController extends BaseController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}
	
	public function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "public/compose_user2user_message.tpl", "" );
	}
	
	public function send_user2user( &$tmpl, &$request )
	{
		$this->myModel->user2user( $request );
		if( $this->myModel->myRes )
			return header( "Location: /message-send.html" );
		else //for now this path is impossible
		{
			$tmpl->assign( "error", $this->myModel->myError );
			return $this->index( $tmpl, $request );
		}
	}

	public function send_user2admin( &$tmpl, &$request )
	{
		$this->myModel->user2admin( $request );

		if( $this->myModel->myRes )
			return header( "Location: /feedback-send.html" );
		else //for now this path is impossible
		{
			$tmpl->assign( "error", $this->myModel->myError );
			return $this->index( $tmpl, $request );
		}
	}
}

?>
