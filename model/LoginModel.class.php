<?php

class LoginModel extends BaseModel 
{
	public $myTitle="Login";
	protected $myTableName="users";
	protected $mySessionData=array( 
							"user_id"=>"user",
							"u_group"=>"group",
							"u_name"=>"fullname" );
							
	public function __construct( $php )
	{
		parent::__construct( $php );
		$this->addColumns( array(
			"login" =>new TextColumn("u_login","Login","Enter your login"),
			"passwd"=>new PasswordColumn("u_passwd","Password"),
		) );
	}
	
	public function clearSessionData( )
	{
		foreach( $this->mySessionData as $key=>$val ) unset( $_SESSION[$val] );
	}
	
	public function tryLogin( &$request )
	{
		global $DB;

		$name=$this->myColumns['login']->getInsert( $request );
		$pass=md5( md5($request[$this->myColumns['passwd']->myName]) );

		$sql_users="SELECT * $this->myExtraSelect FROM $this->myTableName 
						WHERE ".$this->myColumns['login']->myName."=$name AND 
						MD5(MD5(".$this->myColumns['passwd']->myName."))='$pass'";
		$row=$DB->GetRow( $sql_users );
		if( $row )
		{
			foreach( $this->mySessionData as $key=>$val ) $_SESSION[$val]=$row[$key];
			$this->set_default_login( $request );
		}
		else 
			return "Incorrect login name or password";
	}

   	public function get_default_login( &$request )
	{
		global $COOKIES;
		
		if( isset($request[$this->myColumns['login']->myName]) )
		{
			$this->myColumns['login']->myDefault=$request[$this->myColumns['login']->myName];
		}
		else
			$this->myColumns['login']->myDefault=$COOKIES->ReadCookie( "deflogin" );
	}
	
	public function set_default_login( &$request )
	{
		global $COOKIES;
		$COOKIES->WriteCookie( array("deflogin"=>$request[$this->myColumns['login']->myName]) );
	}
}

?>
