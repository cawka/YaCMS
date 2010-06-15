<?php
include_once( "TableModel.class.php" );

class UserRightsModel extends TableModel 
{
	public $myTitle="Разрешенные действия";
	
	public function __construct( $php )
	{
		global $DB,$_REQUEST;
		$user_id=$_REQUEST["user_group_id"];
		
		parent::__construct( $DB,$php,"user_rights",array(
				"user_group_id"=>new HiddenColumn( "user_group_id", $user_id ),
				"controller_id"=>new TextColumn( "controller_id","Контроллер","Укажите контроллер" ),
				"allow_action"=>new TextColumn( "allow_action","Действие","Укажите действие" ),
			)
		);
		$this->myOrder="controller_id,allow_action";
		$this->myParentId="TB_ajaxContent";
	}
	
	public function validateSave( &$request )
	{
		$ret=$this->checkUnique( array("user_group_id","controller_id","allow_action"),$request );
		if( $ret!="" ) return $ret;
		
		return parent::validateSave( $request );
	}
}

?>
