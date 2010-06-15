<?php

		$_SESSION["company"]=$row["u_company"];
		$_SESSION["group"]=$row["u_group"];
		$_SESSION["my_banners"]=$row["my_banners"];

class LoginModel extends BaseModel 
{
	public $defaultLogin="";

	protected $myTableName="users";
	protected $mySessionData=array( 
							"user_id"=>"user",
							"u_group"=>"group",
							"fullname"=>"fullname",
							"u_company"=>"company",
							"my_banners"=>"my_banners",
		);
	protected $myExtraSelect=",u_fname||' '||u_lname as fullname";
							
	public function __construct( $php )
	{
		global $langdata;
		
		parent::__construct( $php );
		
		$this->myColumns=array(
			"login" =>new TextColumn("klogin",$langdata['login_login'],"required" ),
			"passwd"=>new PasswordColumn("kpassword",$langdata['login_pass']),
		);
	}
	
	public function clearSessionData( )
	{
		foreach( $this->mySessionData as $key=>$val ) unset( $_SESSION[$val] );
	}
	
	public function tryLogin( &$request )
	{
		global $DB, $langdata;

		$name=$this->myColumns['login']->getInsert( $request );
		$pass=md5( md5($request[$this->myColumns['passwd']->myName]) );

		$sql_users="SELECT * $this->myExtraSelect FROM $this->myTableName 
						WHERE (u_login=$name OR u_memail=$name) AND
						MD5(MD5(u_passwd))='$pass'";
		$row=$DB->GetRow( $sql_users );
		if( $row )
		{
			foreach( $this->mySessionData as $key=>$val ) $_SESSION[$val]=$row[$key];
			$this->set_default_login( $request );
			
			$DB->Execute( "UPDATE users SET u_lastlogin=NOW() WHERE user_id=".$DB->qstr($row[user_id]) );
		}
		else 
			return $langdata['loginerror'];
	}

   	public function get_default_login( &$request )
	{
		global $COOKIES;
		
		if( isset($request[$this->myColumns['login']->myName]) )
		{
			$this->defaultLogin=$request[$this->myColumns['login']->myName];
		}
		else
			$this->defaultLogin=$COOKIES->ReadCookie( "deflogin" );
	}
	
	public function set_default_login( &$request )
	{
		global $COOKIES;
		$COOKIES->WriteCookie( array("deflogin"=>$request[$this->myColumns['login']->myName]) );
	}
}
