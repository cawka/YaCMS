<?php

class ListDBColumn extends ListColumn 
{
	public $myWhere=array();
	
	protected $myKey;
	protected $myVal;
	protected $myTblName;
	
	function __construct( $name,$descr,$required,$db_name,
						  $db_key,$db_val )
	{
		$this->myTblName=$db_name;
		$this->myKey=$db_key;
		$this->myVal=$db_val;
		$this->myOrder="$this->myVal";
		
		parent::__construct( $name,$descr,$required );
	}
	
	public function getOptions( )
	{
		global $DB,$theAPC;

		$where=array();
		$keys=array( "$this->myTblName", "assoc" );

		foreach( $this->myWhere as $key=>$value )
		{
			$where[]="$key ".(isset($value)?"=".$DB->qstr($value):"IS NULL");
			$keys[]=$value;
		}

		if( sizeof($where)>0 ) $where1="WHERE ".implode( " AND ",$where );

		$this->myOptions=APC_GetAssoc( $keys, $DB,
			"SELECT $this->myKey,$this->myVal FROM $this->myTblName ".
			"$where1 ORDER BY $this->myOrder", 0  );
	}
	
	function getInput( &$row )
	{
		$this->getOptions( );
		return parent::getInput( $row );
	}
	
	function extractValue( &$row )
	{
		if( !isset($this->myOptions) ) $this->getOptions( );
		return parent::extractValue( $row );
	}
	
	function checkBeforeSave( &$row )
	{
		if( !isset($this->myOptions) ) $this->getOptions( );
		return parent::checkBeforeSave( $row );
	}

	function getXML( $row )
	{
		return BaseColumn::getXML( $row );
	}
}
