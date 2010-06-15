<?php

class StaticPagesModel extends TableModel 
{
	public function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"static_pages",array(
				"id"=>new TextIdColumn( "id","Идентификатор страницы" ),
				"sp_title"=>new TextLangColumn("sp_title","Заголовок страницы","Введите заголовок страницы",false),
				"sp_meta"=>new TextAreaColumn( "sp_meta", "Мета информация: ключевые слова <br/><small>для индексации поисковыми системами</small>" ),
				"sp_meta_descr"=>new TextAreaColumn( "sp_meta_descr", "Мета информация: описание <br/><small>для индексации поисковыми системами</small>" ),
//				"sp_text"=>new TextLangTextAreaColumn("sp_text","Текст статической страницы",NULL,false),//,NULL,"100%","600px",false,"",false),
				"sp_text"=>new TextAreaHTMLLangColumn("sp_text","Текст статической страницы",NULL,false),
			),"id" );
		$this->myOrder="id";

		$this->mySearchColumns=array(
			array( "column"=>new TextColumn("id","Идентификатор содержит"), "type"=>"like"),
			array( "column"=>new TextColumn("sp_text","Текст содержит"), "type"=>"like"),
		);
	}

	private function modifyTableName( )
	{
		global $LANG;
		
		$this->myTableName=
" (SELECT id,sp_meta,sp_meta_descr,t1.t_text as sp_title,t2.t_text as sp_text 
		FROM static_pages
			LEFT JOIN texts t1 ON sp_title=t1.t_id AND t1.t_lang_id='$LANG'
			LEFT JOIN texts t2 ON sp_text =t2.t_id AND t2.t_lang_id='$LANG') static_pages " ;
	}
	
	public function collectData( &$request )
	{
		$this->modifyTableName( );		
		return parent::collectData( $request );
	}
	
	public function getRowToShow( &$request )
	{
		$this->modifyTableName( );
		return parent::getRowToShow( $request );
	}
}

?>
