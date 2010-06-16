<?php
include_once( "BaseModel.class.php" );

class TableModel extends TableSortModel
{
	public $myDB;
		
	public $myData;
	public $myId;
	public $myTableName;
	
	public $myOrder;
	public $mySearchColumns;
	
	protected $myExtraWhere="";

	public function __construct( &$db,$php,
						$tblname,$columns,$id="id",$offsets=false )
	{
		$this->myDB=&$db;
		$this->myColumns=$columns;
		$this->myTableName=$tblname;
		$this->myId=$id;

		parent::__construct( $php, $offsets );
	}

	public function isId( )
	{
		if( is_array($this->myData) )
			return isset( $this->myData[$this->myId] );
		else
			return false;
	}
	
	public function getId( )
	{
		return $this->myData[$this->myId];
	}
	
	protected function addSortColumns( $sort )
	{
		$this->mySortColumns=array_merge( $this->mySortColumns, $sort );
 	}

	protected function extraWhere( &$request )
	{
		if( !isset($this->mySearchColumns) ) return $this->myExtraWhere;
		$ret=$this->myExtraWhere;
		foreach( $this->mySearchColumns as &$colname )
		{
			if( isset($colname["name"]) )
				$col=&$this->myColumns[$colname["name"]];
			else
				$col=&$colname["column"];
			if( isset($request[$col->myName]) && $request[$col->myName]!="" )
			{
				if( $ret!="" ) $ret.=" AND ";
				switch( $colname['type'] )
				{
				case "like":
					$ret.="$col->myName like '%".$request[$col->myName]."%'";
					break;
				case "custom":
					$ret.=$colname["where"];
					break;
				default:
					$ret.="$col->myName='".$request[$col->myName]."'";
					break;
				}
			}
		}
		return $ret;
	}

	protected function extraSelect( &$request )
	{
		return array();
	}
	
	protected function collectDataBaseRaw( &$request, $select, $where, $order )
	{
		if( $this->myIsOffset )
		{
			$count=$this->myDB->GetOne( "SELECT count(*) FROM (select * FROM $this->myTableName $where LIMIT $this->myMaxElementCount) c" );
			$offset=$this->getCurPage( $request,$count );

			$res=$this->myDB->SelectLimit( "SELECT $select FROM $this->myTableName $where $order",
										   $this->myElementsPerPage,$offset*$this->myElementsPerPage );
		}
		else
			$res=$this->myDB->Execute( "SELECT $select FROM $this->myTableName $where $order" );

		return $res;
	}
	
	protected function collectDataBase( &$request )
	{
		global $LANG;

		$sort_temp=$request['sort'];
		$sort=NULL;
		foreach( $this->mySortColumns as $key=>&$value ) { if( $key==$sort_temp ) { $sort=$value; break;} }
		if( isset($sort) )
		{
			if( isset($_REQUEST['desc']) )
				$this->myOrder=$sort["desc"];
			else
				$this->myOrder=$sort["asc"];
		}
		
		$sql= " $this->myTableName";
		$where="";
		if( isset($this->myLang) ) $where.=" $this->myLang='$LANG'";
		
		foreach( $this->myColumns as &$col )
		{
			if( !$col->myIsVisible && !isset($col->myIsProtected) ) 
			{ 
				if( $where!="" ) $where.=" AND ";
				$where.=$col->myName;
				if( $col->getInsert( $_REQUEST )=="NULL" )
					$where.=" IS NULL ";
				else 
					$where.="=".$col->getInsert( $request );
			}
		}
		$where.=$this->extraWhere( $request );
		
		if( $where!="" ) $sql.=" WHERE $where";
		if( $this->myIsOffset )
		{
			$count=$this->myDB->GetOne( "SELECT count(*) FROM (SELECT $this->myId FROM $sql LIMIT $this->myMaxElementCount) c" );
			$offset=$this->getCurPage( $request,$count );
		}
		if( $this->myOrder!="" ) $sql.=" ORDER BY $this->myOrder";

		$extraSel=$this->extraSelect( $request );
		if( sizeof($extraSel)!=0 ) $extraSel=",".implode(",",$extraSel); else $extraSel="";
		if( $this->myIsOffset )
			$res=$this->myDB->SelectLimit( "SELECT * $extraSel FROM $sql",$this->myElementsPerPage,$offset*$this->myElementsPerPage );
		else 
			$res=$this->myDB->Execute( "SELECT * $extraSel FROM $sql" );

		return $res;
	}

	public function collectData( &$request )
	{
		$res=$this->collectDataBase( $request );
		$this->myData=$res;//->GetRows( );
	}
	
	public function collectDataFetch( &$request )
	{
		$this->myData=&$this->collectDataBase( $request );
	}
	
	public function getRowToEdit( &$request )
	{
		global $LANG;
		if( isset($request['error']) )
		{
			$this->myData=$request;
		}
		else
		{
			$sql="SELECT * FROM $this->myTableName WHERE ".$this->rowId( $request );
			if( isset($this->myLang) ) $sql.=" AND $this->myLang='$LANG'";
			$this->myData=$this->myDB->GetRow( $sql );
		}
	}

	protected function rowId( $request )
	{
		$ret=$this->myId;
		if( $request[$this->myId]!="" )
			$ret.="=".$this->myDB->qstr($request[$this->myId]);
		else
			$ret.=" IS NULL ";
		return $ret;
	}

	public function getRowToShow( &$request )
	{
		return $this->getRowToEdit( $request ); //by default - same action
	}
	
	public function deleteRow( &$request )
	{
		$id=$request[$this->myId];
		if( isset($id) ) $this->myDB->Execute( "DELETE FROM $this->myTableName WHERE $this->myId='$id'" );
	}
	
	protected function saveRowUpdate( $id, &$request )
	{
		$ret="UPDATE $this->myTableName SET ";
		$i=0;
		foreach( $this->myColumns as $col )
		{
			if( $col->myIsReadonly ) continue;
			if( !$col->mySQL ) 
			{
				$col->getUpdate( $request );
				continue;
			}
			//if( $col->myIsVisible==false ) continue;

			if( $i>0 ) $ret.=",";
			$ret.=$col->getUpdate( $request );
			$i++;			
		}
		$ret.=" WHERE $this->myId";
		if( $id!="" ) $ret.="='$id'"; else $ret.=" IS NULL ";
		
		$this->myDB->Execute( $ret );
		foreach( $this->myColumns as $col ) $col->postUpdate( $id,$request );
	}
	
	protected function saveRowInsert( &$request )
	{
		if( $this->myIsAutoId )
		{
			$id=$this->myDB->GenID( $this->myTableName."_".$this->myId."_seq" );
		}
		
		$ret="INSERT INTO $this->myTableName (";
		if( isset($id) ) $ret.="$this->myId,";
		$i=0;
		foreach( $this->myColumns as $col )
		{
			if( $col->myIsReadonly ) continue;
			if( !$col->mySQL ) continue;
			if( $i>0 ) $ret.=",";
			$ret.=$col->getUpdateName();
			$i++;				
		}
		$ret.=")";
		
		$ret.=" VALUES(";
		if( isset($id) ) $ret.="'$id',";
		$i=0;
		foreach( $this->myColumns as $col )
		{
			if( $col->myIsReadonly ) continue;
			if( !$col->mySQL ) 
			{
				$col->getInsert( $request );
				continue;
			}
			if( $i>0 ) $ret.=",";
			$ret.=$col->getInsert( $request );
			$i++;				
		}
		$ret.=")";	
		
		$this->myDB->Execute( $ret );
		foreach( $this->myColumns as $col ) $col->postInsert( $id,$request );
		
		return $id;
	}
	
	public function save_add( &$request )
	{
		return $this->saveRowInsert( $request );
	}
	
	public function save_edit( &$request )
	{
		$id=$request[$this->myId];
		return $this->saveRowUpdate( $id,$request );
	}
	
	public function checkUnique( $colnames,&$request )
	{
		$sql="SELECT $this->myId FROM $this->myTableName WHERE ";
		$params=array();
		foreach( $colnames as $colname )
		{
			$col=&$this->myColumns[$colname];
			$val=$col->getInsert( $request );
			if( $val=="NULL" ) 
				array_push( $params, "$colname IS NULL" );
			else
				array_push( $params, "$colname=$val" );
		}
		$sql.=implode( " AND ", $params );
		if( $this->myDB->GetOne($sql) ) 
			return "Не уникальная комбинация";
		else
			return "";
	}
	
	public function extractId( &$row )
	{
		return $row[$this->myId];
	}
	

	
	public function isError( $name,&$err_cols )
	{
		return !($err_cols[$name]===null);
	}
}
