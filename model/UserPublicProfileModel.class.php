<?php

class UserPublicProfileModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "(SELECT * from users WHERE user_id='$_REQUEST[user_id]') users", array(
			), "user_id" );
		$this->myLogo=new PartnerPhotoColumn("logo","" );
	}
	
	public function getRowToEdit( &$request )
	{
		global $langdata;
	
		parent::getRowToEdit( $request );
		if( $this->myData['u_group']=='3' ) //Individual Persons
		{
			$this->myColumns=array_merge( $this->myColumns,
				array(
			"u_name"=>new TextColumn("u_name",$langdata['name'] ),
					new PhoneColumn("u_phone",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone2",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone3",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone4",$langdata['cat_phone_num'],"","" ),
					new EmailColumn("u_email","Email",false ),
				)
			);
		}
		else if( $this->myData['u_group']=='2' ) //Companies
		{
			$this->myColumns=array_merge( $this->myColumns,
				array(
					new TextColumn("u_company",$langdata['company'],NULL,false,"","","",true ),
					new TextColumn("u_address",$langdata['address'] ),
					new TextColumn("u_rekv",$langdata['rekviziti']),
		//			new TextColumn("u_mname","Отчество" ),
		//			new PartnerPhotoColumn("u_logo","Логотип",NULL,0,0),
			"u_name"=>new TextColumn("u_name",$langdata['name'] ),
					new PhoneColumn("u_phone",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone2",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone3",$langdata['cat_phone_num'],"","" ),
					new PhoneColumn("u_phone4",$langdata['cat_phone_num'],"","" ),
					new EmailColumn("u_email","Email",false ),
				)
			);
		}
	}
}

?>
