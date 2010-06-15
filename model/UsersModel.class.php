<?php
include_once( "TableModel.class.php" );

class UsersModel extends TableModel
{
	public $myTitle="Пользователи";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
//		$DB->debug=true;
		
		parent::__construct( $DB,$php, "users", array(
			"login"=>	new TextColumn("u_login",  "Логин","Введите логин",true),
				new ListColumn("u_group", "Тип пользователя",true,
					array(	
						0=>"root",
						99=>"Менеджер статей",
						1=>"пользователь",
						2=>"Юридическое лицо",
						3=>"Физическое лицо") ),
				new BooleanColumn("is_active","Пользователь активирован"),
				new BooleanColumn("my_banners","Доступна статистика по баннерам"),
//					new TextColumn("u_login",  "Логин","Введите логин",true),

				new PasswordColumn("u_passwd",     "Пароль",       "Введите пароль",false,"",true,"u_passwd_confirm"),
				new PasswordColumn("u_passwd_confirm", "Повтор пароля","Введите пароль",false,"",false,"u_passwd"),
		"sep1"=>new BaseColumn("sep1","<b>".$langdata['contact_data']."</b>",true,NULL,false,"" ),
		"name"=>new TextColumn("u_lname",$langdata['surname'] ),
				new TextColumn("u_fname",$langdata['name'] ),
				new TextColumn("u_mphone",$langdata['cat_phone_num']),
				new EmailColumn("u_memail","Email",false),
		"sep2"=>new BaseColumn("sep1","<b>".$langdata['public_data']."</b>",true,NULL,false,"" ),
				new TextColumn("u_company",$langdata['company'],NULL,false ),
				new TextColumn("u_address",$langdata['address'] ),
				new TextAreaColumn("u_rekv",$langdata['rekviziti'] ),
		"logo"=>new PartnerPhotoColumn("u_logo",$langdata['logo'],NULL,0,0),
				new BooleanColumn("is_logo_allowed","Разрешается менять логотип"),
				new TextColumn("u_name",$langdata['name'] ),
				new PhoneColumn("u_phone",$langdata['cat_phone_num'],"","" ),
				new EmailColumn("u_email","Email",false ),		
			), "user_id",true );
		$this->myOrder="u_lname,u_fname,u_company";
		
		$this->myColumns["sep1"]->myGenType="separator";
		$this->myColumns["sep1"]->mySQL=false;

		$this->myColumns["sep2"]->myGenType="separator";
		$this->myColumns["sep2"]->mySQL=false;

		$this->myColumns["logo"]->myIsVisible=true;
		$this->myElementsPerPage=30;
		
		$this->mySortColumns=array( 
			"name"=>array(
						"asc"=>"u_lname,u_fname,u_company",
						"desc"=>"u_lname DESC,u_fname DESC,u_company DESC",
						),
			
			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
			"last" =>array("asc"=>"u_lastlogin","desc"=>"u_lastlogin DESC"),
			"adv"  =>array("asc"=>"u_adv_count","desc"=>"u_adv_count DESC"), 
		);
		
		$this->mySearchColumns=array(
			array( "column"=>new ListColumn("is_active","Вывести",NULL,array(
					""=>"Всех",
					"t"=>"Только активных",
					"f"=>"Только неактивных",
				)),"type"=>"exactOrNone" ),
			array( "column"=>new TextColumn("fullname","Компания и имя содержат"),"type"=>"like" ),
			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
			array( "column"=>new TextColumn("u_email","Email"),"type"=>"like" ),
		);
	}
	
	public function collectData( &$request )
	{
		$this->myTableName=
" (SELECT *,
(CASE WHEN u_company IS NOT NULL THEN u_company||' ' ELSE '' END)||
(CASE WHEN u_fname IS NOT NULL THEN u_fname||' ' ELSE '' END)||
(CASE WHEN u_lname IS NOT NULL THEN u_lname||' ' ELSE '' END)
 as fullname FROM users) users ";
		return parent::collectData( $request );
	}
	
	public function recoverLogin( &$request )
	{
		$email=$this->myDB->qstr( $request['email'] );
		$this->myData=$this->myDB->GetRow( "SELECT * FROM users WHERE u_login=$email OR u_memail=$email OR u_email=$email" );
	}
}

?>
