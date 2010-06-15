<?php

class UsersController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/users.tpl","","common/form.tpl"
		);
	}
	
	public function recoverLogin( &$tmpl, &$request )
	{

		$this->myModel->recoverLogin( $request );
		if( $this->myModel->myData )
		{
			$this->myUseSmartyFetch=true;
			
			$txt= $this->showTemplate( $tmpl, $request, "public/recoverPassword.txt.tpl", "" );
			$html=$this->showTemplate( $tmpl, $request, "public/recoverPassword.tpl", "" );
			
			$this->myUseSmartyFetch=false;

			Mail::sendFromRobot( $request['email'], "Password Recovery", $html, $txt );
			return $this->showTemplateDB( $tmpl, $request, "recoverPasswordOK", "" );
		}
		else 
		{
			return $this->showTemplateDB( $tmpl, $request, "recoverPasswordFail", "" );
		}
		
	}
}
