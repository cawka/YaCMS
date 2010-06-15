<?php

class MultiselectColumn extends BaseColumn 
{
	var $myRefTable;
	var $myRefId;
	var $myRefOrder;
	var $myMiddleTable;
	var $myMiddleTableId;
	var $myMiddleRefTableId;
	public $myTableId="id";
	
	function __construct( $name,$descr,$reftable,$refid="id",$midtable,$midid,$midrefid,$refname="name",$reforder="name" )
	{
		$this->myRefTable=$reftable;
		$this->myRefId=$refid;
		$this->myRefName=$refname;
		$this->myRefOrder=$reforder;
		
		$this->myMiddleRefTableId=$midrefid;
		$this->myMiddleTable=$midtable;
		$this->myMiddleTableId=$midid;

		parent::__construct( $name,$descr,true,false,false );
		$this->mySQL=false;
	}
	
	function getInsert( &$request )
	{
		// need to process after actual INSERT
		return "";
	}

	function getUpdate( &$request )
	{
		// need to process after actual INSERT
		return "";
	}
	
	
	function postInsert( $id,&$request )
	{
		global $DB;
		
		$DB->Execute( "DELETE FROM $this->myMiddleTable WHERE $this->myMiddleTableId='$id'" );
		foreach( $request[$this->myName] as $catid )
		{
			$DB->Execute( "INSERT INTO $this->myMiddleTable 
				($this->myMiddleTableId,$this->myMiddleRefTableId) VALUES ('$id','$catid')" );
		}
	}

	function postUpdate( $id,&$data )
	{
		$this->postInsert( $id, $data );
	}

	function getInput( &$row )
	{
		global $DB;
//		$DB->debug=true;
		
//		$id=$row[$this->myTable->myId];
//		print $this->myTable->myId;
		/// @bug: id broken. Have to be modified somehow
		$id=$row[$this->myTableId];
		
		
		if( "$id"!="" )
		{
			$res=$DB->Execute( 
				"SELECT r.*,$this->myMiddleTableId FROM $this->myRefTable r
						LEFT JOIN $this->myMiddleTable m ON m.$this->myMiddleRefTableId=r.$this->myRefId 
													     AND m.$this->myMiddleTableId='$id' 
						ORDER BY r.$this->myRefOrder" );
		}
		else 
			$res=$DB->Execute( "SELECT * FROM $this->myRefTable ORDER BY $this->myRefOrder" );
			
		$ret="<SELECT name='$this->myName[]' class=\"addann_textarea\" multiple='multiple'>\n";
		foreach( $res as $data )
		{
			$ret.="<option value='".$data[$this->myRefId]."'";
			if( $data[$this->myMiddleTableId]!==null ) $ret.=" selected='selected'";
			$ret.=">".$data[$this->myRefName]."</option>\n";
		}
		$ret.="</SELECT>\n";
		return $ret;
	}
}


?>