<?php

class StaticTextsModel extends TableModel 
{
	public function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"static_texts",array(
				new TextColumn( "st_name",    "Внутреннее уникальное имя поля","Введите название поля",true),
				"text"=>new TextAreaLangColumn( "st_text", "Текст",NULL,false ),
			),"st_id",true 
		);
		$this->myElementsPerPage=40;
		$this->myColumns['text']->myLimit=9999999;
		
		$this->mySearchColumns=array(
			array( "column"=>new TextColumn("st_name","Идентификатор содержит"), "type"=>"like"),
			array( "column"=>new TextColumn("st_text","Текст содержит"), "type"=>"like"),
		);
		$this->myOrder="st_name";
	}
	
	public function collectData( &$request )
	{
		global $LANG;
		$this->myTableName=" (SELECT s.st_id, s.st_name, t.t_text AS st_text, s.st_text AS st_text_id
   FROM static_texts s
   JOIN texts t ON t.t_id = s.st_text AND t.t_lang_id='$LANG') texts ";
		return parent::collectData( $request );
	}
}

?>
