<?php
include_once( "TableModel.class.php" );

class TableRecursiveModel extends TableModel 
{
	var $myIsRecursive=false;
	var $myParentIdName="";
	var $myListHeader="";
	var $myForceRecursive=false;

	function extractParentId( &$row )
	{
		return $row[$this->myParentIdName];
	}

}

?>
