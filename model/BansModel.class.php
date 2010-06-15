<?php

class BansModel extends TableModel 
{
	function __construct( $php )
	{
		global $DB;
//		$DB->debug=true;
		parent::__construct( $DB,$php,"bans",array(
				new ListColumn("type","Тип бана",NULL,array(
						"ip"=>"IP адрес",
						"phone"=>"Телефон",
						"any"=>"Где угодно",
					)),
				new TextColumn("value","Значение<br/><small>Можно использовать '%' для соответствия любым символам</small>"),
				new TextAreaColumn("reason","Причина бана"),
			)
		);

		$this->mySearchColumns=array(
			array( "column"=>new ListColumn("type","Тип бана",NULL,
					array(
						""=>"Все типы",
						"ip"=>"IP адрес",
						"phone"=>"Телефон",
					)), "type"=>"like"),
			array( "column"=>new TextColumn("value","Бан содержит"), "type"=>"like"),
		);
		$this->myOrder="type,value";
	}
	
	public function save_add( &$request )
	{
		array_push( $this->myColumns, new HiddenExactValueColumn("datetime","NOW()",false) );
		return parent::save_add( $request );
	}
	
	public function save_edit( &$request )
	{
		array_push( $this->myColumns, new HiddenExactValueColumn("datetime","NOW()",false) );
		return parent::save_edit( $request );
	}
}
