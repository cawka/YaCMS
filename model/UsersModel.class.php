<?php

class UsersModel extends TableModel
{
	public $myTitle="Users";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
//		$DB->debug=true;
		
		parent::__construct( $DB,$php, "users", array(
			"login"=>	new TextColumn("u_login",  "Login","Enter user&rsquo;s login",true),
				new TextColumn( "u_name",  "Name",  "Enter user&rsquo;s name" ),
				new EmailColumn("u_email", "Email", true, "Enter user&rsquo;s email" ),

				new PasswordColumn("u_passwd",  "Password",       "Enter password",false,"",true,"u_passwd2"),
				new PasswordColumn("u_passwd2", "Repeat password","Repeat password",false,"",false,"u_passwd"),
			), "user_id",NULL,"",true );
		$this->myOrder="u_login";
		
		$this->myElementsPerPage=30;
		
/*		$this->mySearchColumns=array(
			array( "column"=>new TextColumn("u_login","in login"),"type"=>"like" ),
			array( "column"=>new TextColumn("u_email","in email"),"type"=>"like" ),
	);*/
	}
	
	public function recoverLogin( &$request )
	{
		$email=$this->myDB->qstr( $request['email'] );
		$this->myData=$this->myDB->GetRow( "SELECT * FROM users WHERE u_email=$email" );
	}
}

?>
