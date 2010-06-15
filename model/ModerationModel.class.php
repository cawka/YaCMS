<?php

class ModerationModel extends TableModel
{
	public $myTitle="Модерация объявлений";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "(SELECT * FROM data WHERE flag_category IS NULL AND flag_moderated IS NULL or flag_moderated='f') data", array(
			), "id",true );
		$this->myOrder="publ_begin DESC";
		$this->myElementsPerPage=100;

		$this->myMaxElementCount=100000000;
	}
	
	public function deleteRow( &$request )
	{
		if( !is_array($request["favdel"]) || sizeof($request["favdel"])==0 )
		{
			$this->myTableName="data";
			parent::deleteRow( $request );
			$this->myTableName="(SELECT * FROM data WHERE flag_category IS NULL AND (flag_moderated IS NULL or flag_moderated='f')) data";
		}
		else
		{
			$list=implode( ",", $request["favdel"] );
			$this->myDB->Execute( "DELETE FROM data WHERE id IN ($list)" );
		}
	}

	public function commit( &$request )
	{
		if( !is_array($request["favdel"]) || sizeof($request["favdel"])==0 ) return;
		$list=implode( ",", $request["favdel"] );
		$this->myDB->Execute( "UPDATE data SET flag_moderated='t' WHERE id IN ($list)" );
	}
}

?>
