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
	protected $mySelect="*";
	protected $myGroup="";

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
			return isset( $this->myData[$this->myId] ) && !isset($this->myData['new_item']);
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

	protected function formatWhere( &$request, &$col, &$colname )
	{
		global $DB;

		$ret="";

		if( isset($request[$col->myName]) && strlen($request[$col->myName])!=0 )
		{
			switch( $colname['type'] )
			{
			case "equal":
				$ret.="$colname[field]=".$DB->qstr($request[$col->myName]);
				break;
			case "ge":
				$ret.="$colname[field]>".$DB->qstr($request[$col->myName]);
				break;
			case "geq":
				$ret.="$colname[field]>=".$DB->qstr($request[$col->myName]);
				break;
			case "le":
				$ret.="$colname[field]<".$DB->qstr($request[$col->myName]);
				break;
			case "leq":
				$ret.="$colname[field]<=".$DB->qstr($request[$col->myName]);
				break;
			case "like_prefix":
				$ret.="$colname[field] like ".$DB->qstr($request[$col->myName]."%");
				break;
			case "like_any":
				$ret.="$colname[field] like ".$DB->qstr("%".$request[$col->myName]."%");
				break;
			case "custom":
				$ret.=$colname["where"];
				break;
			case "bool_int_geq":
				if( $request[$colname['bool']]=='t' && is_numeric($request[$colname['int']]) )
				{
					$ret.="$colname[field]>=".$DB->qstr($request[$colname['int']]);
				}
				break;
			case "bool_int_leq":
				if( $request[$colname['bool']]=='t' && is_numeric($request[$colname['int']]) )
				{
					$ret.="$colname[field]<=".$DB->qstr($request[$colname['int']]);
				}
				break;
			case "bool_list":
				$fields=$colname['fields'];
				if( isset($fields[$request[$col->myName]]) )
				{
					$ret.=$fields[$request[$col->myName]]."=1";
				}
				break;
			}
		}
		return $ret;
	}

	protected function processSearch( &$request, &$group )
	{
		$ret="";

		foreach( $group as &$item )
		{
			if( is_array($item['group']) )
			{
				$x=$this->processSearch( $request, $item['group'] );
				if( $x!="" && $ret!="" ) $ret.=" AND ";
				$ret.=$x;
			}
			else
			{
				$col=$item['column'];

				$x=$this->formatWhere( $request, $col, $item );
				if( $x!="" && $ret!="" ) $ret.=" AND ";
				$ret.=$x;
			}
		}

		return $ret;
	}

	protected function extraWhere( &$request )
	{
		global $DB;

		if( !isset($this->mySearchColumns) ) return $this->myExtraWhere;
		$ret=$this->myExtraWhere;

		$x=$this->processSearch( $request, $this->mySearchColumns );
		if( $x!="" && $ret!="" ) $ret.=" AND ";
		$ret.=$x;

		return $ret;
	}

	protected function extraSelect( &$request )
	{
		return array();
	}
	
	protected function collectDataBaseRaw( &$request, $select, $where, $order, $group="" )
	{
		if( $this->myIsOffset )
		{
			if( $this->myMaxElementCount>0 )
				$count=$this->myDB->GetOne( "SELECT count(*) FROM (select * FROM $this->myTableName $where $group LIMIT $this->myMaxElementCount) c" );
			else
				$count=$this->myDB->GetOne( "SELECT count(*) FROM $this->myTableName $where $group" );

			$offset=$this->getCurPage( $request,$count );

			$res=$this->myDB->SelectLimit( "SELECT $select FROM $this->myTableName $where $group $order",
										   $this->myElementsPerPage,$offset*$this->myElementsPerPage );
		}
		else
			$res=$this->myDB->Execute( "SELECT $select FROM $this->myTableName $where $group $order" );

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
			if( isset($request['desc']) )
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
				if( $col->getInsert( $request )=="NULL" )
					$where.=" IS NULL ";
				else 
					$where.="=".$col->getInsert( $request );
			}
		}
		$where2=$this->extraWhere( $request );
		if( $where!="" && $where2!="" ) $where.=" AND ";
		$where.=$where2;
		
		if( $where!="" ) $sql.=" WHERE $where ";
		$sql.=" $this->myGroup ";

		if( $this->myIsOffset )
		{
			if( $this->myMaxElementCount>0 )
				$count=$this->myDB->GetOne( "SELECT count(*) FROM (SELECT $this->mySelect FROM $sql LIMIT $this->myMaxElementCount) c" );
			else
				$count=$this->myDB->GetOne( "SELECT count(*) FROM $sql" );

			$offset=$this->getCurPage( $request,$count );
		}
		if( $this->myOrder!="" ) $sql.=" ORDER BY $this->myOrder";

		$extraSel=$this->extraSelect( $request );
		if( sizeof($extraSel)!=0 ) $extraSel=",".implode(",",$extraSel); else $extraSel="";
		if( $this->myIsOffset )
			$res=$this->myDB->SelectLimit( "SELECT $this->mySelect $extraSel FROM $sql",$this->myElementsPerPage,$offset*$this->myElementsPerPage );
		else 
			$res=$this->myDB->Execute( "SELECT $this->mySelect $extraSel FROM $sql" );

		return $res;
	}

	public function collectData( &$request )
	{
		$res=$this->collectDataBase( $request );
		$this->myData=$res;//->GetRows( );
	}
	
	public function collectDataFetch( &$request )
	{
		$this->myData=$this->collectDataBase( $request );
	}

	public function getRowFromRequest( &$request )
	{
		$this->myData=$request;
		$this->myData['new_item']=true;
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

			if( !$this->myData )
			{
				$this->myData=$request;
				$this->myData['new_item']=true;
			}
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
		

		#$sql="DELETE FROM $this->myTableName WHERE $this->myId='$id'";
		$sql=sprintf("DELETE FROM %s WHERE %s=%s",
		             $this->myTableName, $this->myId, $this->myDB->qstr($id));
		if( $this->myExtraWhere!="" ) $sql.=" AND ".$this->myExtraWhere;

		if( isset($id) )
		{ 
			$this->myDB->Execute( $sql );
		}
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
		if( $this->myExtraWhere!="" ) $ret.=" AND ".$this->myExtraWhere;
		
		$this->myDB->Execute( $ret );
		foreach( $this->myColumns as $col ) $col->postUpdate( $id,$request );
	}
	
	protected function saveRowInsert( &$request )
	{
		if( $this->myIsAutoId )
		{
			$id=$this->myDB->GenID( $this->myTableName."_".$this->myId."_seq" );
		}
		
		$ret="INSERT IGNORE INTO $this->myTableName (";
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
