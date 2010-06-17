<?php

class UserGroupsModel extends TableModel 
{
	var $myTitle="User groups";
	
	function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"user_groups",array(
			new TextColumn( "name","User group name","Enter user group name"),
		) );
	}
	
	public function collectData( &$request )
	{
		parent::collectData( $request );
		$this->myData=array_merge( array(array("name"=>"Public access")),$this->myData );
	}
}

?>
