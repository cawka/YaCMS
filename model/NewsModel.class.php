<?php

class NewsModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "news", array(
			new DateColumn("publ_begin","Начало публикации"),
			new DateColumn("publ_end", "Окончание публикации"),
			new BooleanColumn("top","Новость на главной"),
			new TextLangColumn("subject", "Тема новости",NULL,false),
			new TextAreaLangColumn("body", "Текст новости",NULL,false),
			), "id",true );
		$this->myOrder="top NULLS LAST, publ_begin DESC";
//		$this->myElementsPerPage=30;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}
	
	public function collectData( &$request )
	{
		global $LANG;
//		$this->myDB->debug=true;
		$this->myTableName=" (SELECT *,t1.t_text as subject,t2.t_text as body 
								FROM news n 
									JOIN texts t1 ON t1.t_lang_id='$LANG' AND t1.t_id=n.subject
									JOIN texts t2 ON t2.t_lang_id='$LANG' AND t2.t_id=n.body
									) news ";
		return parent::collectData( $request );
	}
	
//	public function save_add( &$request )
//	{
//		$this->myDB->debug=true;
//		parent::save_add( $request );
//		die;
//	}
}

?>
