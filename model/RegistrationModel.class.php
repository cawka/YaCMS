<?php

class RegistrationModel extends TableModel
{
	public $myGroup=2;
	
	public function	 __construct( $php )
	{
		global $DB,$langdata,$LANG;

		if( isset($_REQUEST['group']) ) $this->myGroup=$_REQUEST['group'];

		parent::__construct( $DB, "registration", "users",
			array(
		"login"=>new LoginColumn("u_login","Email/".$langdata['reg_login'],true,$langdata['reg_login_help'],false,$langdata['reg_login']),
				new PasswordColumn("u_passwd",$langdata['reg_passwd'],$langdata['reg_passwd_help'],false,$langdata['reg_passwd'],true,"u_passwd2"),
				new PasswordColumn("u_passwd2",$langdata['reg_passwd2'],$langdata['reg_passwd2_help'],false,"",false,"u_passwd"),

		"sep1"=>new BaseColumn("sep1","<b>".$langdata['contact_data']."</b>",true,NULL,false,"<b>".$langdata['contact_data']."</b>" ),
			),"user_id" );

		if( $this->myGroup==2 )
		{
			$this->myColumns=array_merge( $this->myColumns, array(
				new TextColumn("u_company",$langdata['company'],NULL,false,$langdata['company'],"","",true ),
				new TextColumn("u_address",$langdata['address'],NULL,false,$langdata['address'] ),
				new TextAreaColumn("u_rekv",$langdata['rekviziti'],NULL,false,$langdata['rekviziti'] ),
				new TextColumn("u_lname",$langdata['surname'],NULL,false,$langdata['surname'] ),
				new TextColumn("u_fname",$langdata['name'] ,NULL,false,$langdata['name']),
				new TextColumn("u_mphone",$langdata['cat_phone_num'],NULL,false,$langdata['cat_phone_num']),
//				new EmailColumn("u_memail","Email",true,$langdata['reg_email_help'],false,"Email"),

		"sep2"=>new BaseColumn("sep1","<b>".$langdata['public_data']."</b>",true,NULL,false,"<b>".$langdata['public_data']."</b>" ),
				new StaticColumn("u_company",$langdata['company'] ),
				new StaticColumn("u_address",$langdata['address'] ),
//				new StaticColumn($this,"u_rekv",$langdata['rekviziti'] ),

				new TextColumn("u_name",$langdata['name'],NULL,false,$langdata['name'] ),
		"phone"=>new PhoneColumn("u_phone",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
	   	"phone2"=>new PhoneColumn("u_phone2",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
	   	"phone3"=>new PhoneColumn("u_phone3",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
		"phone4"=>new PhoneColumn("u_phone4",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
//		        new PhoneColumn("u_phone5",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
				new EmailColumn("u_email","Email",false,NULL,false,"Email" ),
			) );
		}
		else
		{
			$this->myColumns=array_merge( $this->myColumns, array(
				new TextColumn("u_lname",$langdata['surname'],NULL,false,$langdata['surname'] ),
				new TextColumn("u_fname",$langdata['name'] ,NULL,false,$langdata['name']),
				new TextColumn("u_mphone",$langdata['cat_phone_num'],NULL,false,$langdata['cat_phone_num']),
//				new EmailColumn("u_memail","Email",true,$langdata['reg_email_help'],false,"Email"),

		"sep2"=>new BaseColumn("sep1","<b>".$langdata['public_data']."</b>",true,NULL,false,"<b>".$langdata['public_data']."</b>" ),
				new TextColumn("u_name",$langdata['name'],NULL,false,$langdata['name'] ),
		"phone"=>new PhoneColumn("u_phone",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
	   	"phone2"=>new PhoneColumn("u_phone2",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
	   	"phone3"=>new PhoneColumn("u_phone3",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
		"phone4"=>new PhoneColumn("u_phone4",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
//		        new PhoneColumn("u_phone5",$langdata['cat_phone_num'],NULL,$langdata['cat_phone_num'] ),
				new EmailColumn("u_email","Email",false,NULL,false,"Email" ),
			) );
		}

		$this->myColumns['phone']->myToolTip=$langdata['reg_help_phone1'];
		$this->myColumns['phone2']->myToolTip=$langdata['reg_help_phone1'];
		$this->myColumns['phone3']->myToolTip=$langdata['reg_help_phone1'];
		$this->myColumns['phone4']->myToolTip=$langdata['reg_help_phone1'];

		$this->myIsAutoId=true;

		$this->myColumns["login"]->myIsReadonly=false;

		$this->myColumns["sep1"]->myGenType="separator";
		$this->myColumns["sep1"]->mySQL=false;

		$this->myColumns["sep2"]->myGenType="separator";
		$this->myColumns["sep2"]->mySQL=false;
	}

	public function prepare( &$request )
	{
		$_REQUEST[$this->myId]=null; //only new form alowed
		$_REQUEST['u_group']=$this->myGroup;
	}

	function save_add( &$request )
	{
		global $langdata;

		$request[$this->myId]=null;

		if( !($request['u_group']==2 || $request['u_group']==3) ) $request['u_group']=2;

		$this->myColumns[]=new HiddenExactValueColumn("u_group",$request['u_group']);
		$this->myColumns[]=new HiddenExactValueColumn("is_active","t");
		$this->myColumns[]=new HiddenExactValueColumn("mydate", "NOW()",false);
		$this->myColumns[]=new HiddenExactValueColumn("is_logo_allowed","f");

		$this->myNewRowId=parent::save_add( $request );
	}
}
