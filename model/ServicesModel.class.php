<?php

class ServicesModel extends TableModel 
{
	public function __construct( $php )
	{
		global $DB;
		
		parent::__construct( $DB,$php,"services",array(
				new TextColumn("name","Описание услуги"),
				new TextColumn("price","Цена за единицу услуги",NULL,false,"",NULL,"грн"),
				new TextColumn("duration","Длительность услуги",NULL,false,"",NULL,"дней"),
			) );
		$this->myOrder="name";
	}
}

?>
