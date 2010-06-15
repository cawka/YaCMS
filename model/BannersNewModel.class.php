<?php

class BannersNewModel extends TableModel 
{
	public $myTitle="Баннеры";
	
	function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"banners",array(
					new TextColumn("b_name","Название баннера",NULL,true),
					new ListDBColumn("b_user_id","Владелец баннера",NULL,"v_users","user_id","u_name"),
					"pos"=>new ListColumn("b_pos","Положение и размер баннера",NULL,array(
							0 => "A1 Баннер в заголовке 728х90 (большой)",
							4 => "A2 Внутристраничнй баннер 680х85",
							//3 => "Баннер в заголовке маленький",
							2 => "A3 Баннер слева 200х310",
							1 => "A4 Баннер слева 200х155",
							5 => "A5 Баннер слева 200х155",
							6 => "Спец.баннер",
							7 => "Вертикальный баннер (240x)",
						),-1,-1),
					new ListColumn("b_type","Вид баннера",NULL,array(
							0 => "Картинка (jpg,gif,...)",
							1 => "Flash",
							2 => "Текст (HMTL)",
							3 => "Внешний баннер - вставка кода (Google,Yandex,...)	",
						),-1,-1),
					new TextColumn( "b_height","Высота баннера (только для вертикального баннера)" ),
					new TextColumn( "b_softid","Приритет баннера" ),
					new BooleanColumn("b_isactive",  "Баннер активен",NULL,false),
					
					new BooleanColumn("is_main",  "==> Показывать на главной <=="),
					new BooleanColumn("is_catalog",  "==> Показывать в каталогах <=="),
					new BooleanColumn("is_list",  "==> Показывать в списках объявлений <=="),
//					new BooleanColumn("is_show",  "==> Показывать при просмотре объявления <=="),
					
					new TextAreaColumn("b_link",  "Ссылка",NULL,false),
					new PhotoLangTextAreaColumn("b_banner",  "Картинка баннера",true,NULL,-1,-1),
			),"b_id"
		);

//		$this->mySearchColumns=array(
//			array( "column"=>new ListColumn("type","Тип бана",NULL,
//					array(
//						""=>"Все типы",
//						"ip"=>"IP адрес",
//						"phone"=>"Телефон",
//					)), "type"=>"like"),
//			array( "column"=>new TextColumn("value","Бан содержит"), "type"=>"like"),
//		);
		$this->myOrder="b_pos,b_user_id,b_name";
	}
	
	function getIP( )
	{
		if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) 
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else 
		{
			$ip=$_SERVER['REMOTE_ADDR'];;
		}
		return $ip;
	}	
	
	function getBannerLink( &$request )
	{
		$id=$request['bid']; 
		$id2=$request['bcid'];

		$nonunique=$this->myDB->GetOne( "SELECT id FROM banner_clicks WHERE date>NOW()-'24 hours'::interval AND
			b_id=".$this->myDB->qstr($id)." AND ip=".$this->myDB->qstr($this->getIP()) );

		if( !$nonunique )
		{
			$this->myDB->Execute( "select insert_stat(now()::timestamp,'$id2',0,1)" );
			$this->myDB->Execute( "INSERT INTO banner_clicks (b_id,ip,date) 
					VALUES(".$this->myDB->qstr($id).",".$this->myDB->qstr(BannerHelper::getIP()).",NOW())" );

		}
		return $this->myDB->GetOne( "SELECT b_link FROM banners WHERE b_id='$id'" ); 
	}
	
}
