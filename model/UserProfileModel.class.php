<?php

class UserProfileModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		$info=$DB->GetRow( "SELECT * FROM users WHERE user_id='".$_SESSION["user"]."'" );
		$is_logo=$info['is_logo_allowed'];

		$columns=array(
//			new TextColumn("u_login","Логин",NULL,false,"","","",true),
	"sep1"=>new BaseColumn("sep1","<b>".$langdata['contact_data']."</b>",true,NULL,false,"" ),
		);
		
		if( $info['u_group']!='3' )
			$columns=array_merge( $columns, array(
				new TextColumn("u_company",$langdata['company'] ),
				new TextColumn("u_address",$langdata['address'] ),
				new TextAreaColumn("u_rekv",$langdata['rekviziti'] ),
			) );

		$columns=array_merge( $columns, array(
			new TextColumn("u_lname",$langdata['surname'] ),
			new TextColumn("u_fname",$langdata['name'] ),
			new TextColumn("u_mphone",$langdata['cat_phone_num']),
			new EmailColumn("u_memail","Email",false),
			new PasswordColumn("u_passwd",$langdata['reg_passwd'],$langdata['reg_passwd_help'],false,$langdata['reg_passwd'],true,"u_passwd2"),
			new PasswordColumn("u_passwd2",$langdata['reg_passwd2'],$langdata['reg_passwd2_help'],false,"",false,"u_passwd"),

			
//	"sep3"=>new BaseColumn("sep1","<b>".$langdata['licevoy_schet']."</b>",true,NULL,false,"" ),
//			new AccountColumn("balance",$langdata['balans_scheta']),
	"sep2"=>new BaseColumn("sep1","<b>".$langdata['public_data']."</b>",true,NULL,false,"" ),
		) );

		if( $info['u_group']!='3' )
			$columns=array_merge( $columns, array(
				new StaticColumn("u_company",$langdata['company'] ),
				new StaticColumn("u_address",$langdata['address'] ),
				new StaticColumn("u_rekv",$langdata['rekviziti'] ),
			) );
		
//		if( $is_logo=='t' )
//			$columns["logo"]=new PartnerPhotoColumn("u_logo",$langdata['logo'],NULL,0,0);
		
		$columns=array_merge( $columns, array(
			"u_name"=>new TextColumn("u_name",$langdata['name'] ),
			"u_phone"=>new PhoneColumn("u_phone",$langdata['cat_phone_num']    ),//,"","" ),
			"u_phone2"=>new PhoneColumn("u_phone2" ,$langdata['cat_phone_num'] ),//,"","" ),
			"u_phone3"=>new PhoneColumn("u_phone3" ,$langdata['cat_phone_num'] ),//,"","" ),
			"u_phone4"=>new PhoneColumn("u_phone4" ,$langdata['cat_phone_num'] ),//,"","" ),
//			"u_phone5"=>new PhoneColumn("u_phone5",$langdata['cat_phone_num'],"","" ),
			"u_email"=>new EmailColumn("u_email","Email",false ),
		) );

		$columns['u_phone']->myToolTip=$langdata['reg_help_phone1'];
		$columns['u_phone2']->myToolTip=$langdata['reg_help_phone2'];
		$columns['u_phone3']->myToolTip=$langdata['reg_help_phone3'];
		$columns['u_phone4']->myToolTip=$langdata['reg_help_phone4'];
		
		$columns["sep1"]->myGenType="separator";
		$columns["sep1"]->mySQL=false;

		$columns["sep2"]->myGenType="separator";
		$columns["sep2"]->mySQL=false;

//		$columns["sep3"]->myGenType="separator";
//		$columns["sep3"]->mySQL=false;

		parent::__construct( $DB,$php, "users", $columns, "user_id" );
//		$this->myOrder="";
//		$this->myElementsPerPage=30;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}

	public function getRowToEdit( &$request )
	{
		$request['user_id']=$_SESSION['user']; //restrict editing
		return parent::getRowToEdit( $request );
	}
	
	public function save_edit( &$request )
	{
		$request['user_id']=$_SESSION['user']; //restrict editing
		return parent::save_edit( $request );
	}
	
	
}

?>
