<?php
include_once( "TableModel.class.php" );

class UserGroupsModel extends TableModel 
{
	var $myTitle="Группы пользователей";
	
	function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"user_groups",array(
			new TextColumn( "name","Название группы пользователей","Введите название группы пользователей"),
		) );
	}
	
	public function collectData( &$request )
	{
		parent::collectData( $request );
		$this->myData=$this->myData->GetRows();
		$this->myData=array_merge( array(array("name"=>"Публичный доступ")),$this->myData );
	}
}

?>
