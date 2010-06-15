<?php

class AutoTableAttributesModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "attrs", array(
			new HiddenColumn(  "attr_type_id",$_REQUEST['attr_type_id']),
			new TextLangColumn("attr_descr", "Описание поля", NULL, false ),
			"name"=>new TextColumn("attr_name",  "Название поля БД","Введите название поля",true),
			
			new ListColumn(    "attr_type",  "Тип поля","Выберите тип поля",
				array(1=>"Текстовое поле (1 строка)",
					  6=>"Текстовое поле (5 строк)",
					  17=>"Спец. поле - текст объявления (5 строк)",
					  2=>"Числовое поле (int)",
					  12=>"Числовое поле (double)",
					  3=>"1 фотография",
					  4=>"5 фотографий",
					  20=>"Логотип пользователя (маленькая картинка)",
					  5=>"Ценовое поле",
					  16=>"Ценовое поле с дополнительным списком",
					  18=>"Ценовое поле с торгом",
					  7=>"Email",
					  8=>"Checkbox (поле Да/Нет)",
					  9=>"Набор checkbox'ов",
					  19=>"Набор checkbox'ов (скрыто по умолчанию, с плюсиком для открытия)",
	//				  10=>"Проект дома",
					  11=>"Combobox (селект)",
					  13=>"---- Separator ----",
					  14=>"---- Регион -----",
					  15=>"Площадь (выбор м2, сотка, га)",
					 )
				),

			"req"=>new BooleanColumn( "attr_req",    "Обязательно для заполнения"),
			
			new TextLangColumn("attr_req_msg","Сообщение пользователю, если поле обязательное, а пользователь не ввел данных", NULL, false),
			new TextLangColumn("attr_brief_msg","Строка при выводе краткого содержания", NULL, false),
			new IntegerColumn( "attr_order",  "Порядок показа полей при подаче объявления",NULL,false,"","0"),
			new IntegerColumn( "attr_brief_order",  "Порядок показа полей в табличном выводе объявления",NULL,false,"","0"),
			new BooleanColumn( "show_in_text", "Блокировать вывод в тексте объявления"),
			new IntegerColumn( "attr_post_order",   "Порядок показа полей при просмотре объявления",NULL,false,"","0"),
			new IntegerColumn( "attr_top_order",   "Порядок показа в TOP объявлениях" ),
			new BooleanColumn( "is_brief_in_top",  "Выводить название поля в TOP объявлениях" ),
			new IntegerColumn( "attr_group",  "Группировка вывода в таблице. Если не задано значение или 0, то не выводится"),
			"attr_issort"=>new BooleanColumn( "attr_issort", "Разрешить сортировку по этому полю"),
			"attr_sortbyname"=>new TextLangColumn("attr_sortbyname","Название в combobox для сортировки", NULL, false),
			new TextColumn    ("attr_options",    "Опции (без различения языка)",NULL,false),
			new TextLangColumn("attr_options_msg","Опции (с различением языка)", NULL, false),
			new TextColumn    ("attr_options2",    "Доп опции (без различения языка)",NULL,false),
			new TextLangColumn("attr_tooltip","Подсказка по полю", NULL, false),
			), "attr_id" );
		$this->myOrder="attr_order";


		$this->myTitle=$DB->GetOne( "SELECT type_name FROM cat_types ".
										"WHERE type_id=".
										$DB->qstr($_REQUEST['attr_type_id']) );

		$this->mySortColumns=array(
			"id"	=>	array("asc"=>"attr_id",			"desc"=>"attr_id DESC"),
			"order"	=>	array("asc"=>"attr_order",		"desc"=>"attr_order DESC"),
			"brief"	=>	array("asc"=>"attr_brief_order","desc"=>"attr_brief_order DESC"),
			"post"	=>	array("asc"=>"attr_post_order",	"desc"=>"attr_post_order DESC"),
			"top"	=>	array("asc"=>"attr_top_order",	"desc"=>"attr_top_order DESC"),
			"group"	=>	array("asc"=>"attr_group",		"desc"=>"attr_group DESC"),
		);

//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}

	public function collectData( &$request )
	{
		$this->myTableName="attrs_lang1";
		return parent::collectData( $request );
	}

	public function denormalize( )
	{
		$this->myDB->Execute( "SELECT denormalize_attrs()" );
	}
}
