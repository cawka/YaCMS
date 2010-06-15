<?php

class AutoTableTypesModel extends TableModel
{
	public $myTitle="Типы каталогов";
	
	public function	 __construct( $php )
	{
		global $DB;
		
		parent::__construct( $DB,$php, "cat_types", array(
			new TextColumn("type_name",         "Название типа каталога","Введите название",true),
			new TextColumn("type_php",          "PHP скрипт" ),
			new TextColumn("type_template",     "Шаблон вывода списка" ),
			new TextColumn("type_template_form","Шаблон вывода формы" ),
			new TextColumn("type_data",         "Название таблицы" ),
			new CreateOrReplaceTableColumn("type_data","Пересоздать таблицу. Работает только в режиме редактирования. <strong>Внимание!!! Эта опция удалит все объявления этого типа!!!</strong>")
			), "type_id" );
		$table->myOrder="type_name";
		
//		$this->myElementsPerPage=30;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}
}

