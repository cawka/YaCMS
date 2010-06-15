<?php

class RegistrationController extends BaseController
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}

	public function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "public/register.tpl", "prepare" );
	}

	public function submit( &$tmpl, &$request )
	{
		global $langdata;
		
		$status=$this->myModel->validateSave( $request );
		if( $status!=""  )
		{
			$request['error']=$status;
			$this->index( $tmpl, $request );
			exit( 0 );
		}
		$this->myModel->save_add( $request );

		$tmpl->assign( "msg", $langdata['regact_ok'] );
		return $this->showTemplate( $tmpl, $request, "public/register_waitemail.tpl" );
	}
}
