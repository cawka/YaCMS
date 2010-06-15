<?php

class DataSaveRestoreHelper
{
	var $myTableInfos=array();
	var $myData=array();
	
	function saveTableInfo( $table,$skip=array() )
	{
		global $DB;
		
		$res=$DB->Execute( "select column_name from INFORMATION_SCHEMA.columns where table_name='$table'");
		$cols=array();
		foreach( $res as $r )
		{
			if( !isIn($r['column_name'],$skip) ) array_push( $cols,$r['column_name'] );
		}
		
		if( sizeof($cols)==0 ) return false;
		$this->myTableInfos[$table]=$cols;
		
		return true;
	}
	
	function saveProductData( $table,$id=null )
	{
		global $DB;
		
		if( isset($id) )
		{
			$where.="id=".$DB->qstr($id);
			
			$res=$DB->Execute( "SELECT * FROM data WHERE $where" );
			$this->myData["data"]=$res->GetRows( );

			$res=$DB->Execute( "SELECT * FROM ann_$table WHERE $where" );
			$this->myData[$table]=$res->GetRows( );
		}
		else
		{
			$res=$DB->Execute( "SELECT * FROM data WHERE cat_id IN (SELECT cat_id FROM catalog WHERE cat_type=(SELECT type_id FROM cat_types WHERE type_data='$table'))" );
			$this->myData["data"]=$res->GetRows( );

			$res=$DB->Execute( "SELECT * FROM ann_$table" );
			$this->myData[$table]=$res->GetRows( );
		}
	}
	
	function saveData( $table,$id=null )
	{
		$this->saveTableInfo( "ann_$table" );
		$this->saveTableInfo( "data" );

		$this->saveProductData( "$table", $id );
		
		return true;
	}
	
	function restore( $table, &$data )
	{
		global $DB;
		
		foreach( $data as &$val )
		{
			$names=array();
			$values=array();
			
			foreach( $this->myTableInfos[$table] as &$col )
			{
				if( isset($val[$col]) )
				{
					array_push( $names, $col );
					$vvv=str_replace( "'", "\\'", $val[$col] );
					array_push( $values, "E'$vvv'" );
				}
			}

			$names_str=implode( ",",$names );
			$values_str=implode( ",",$values );
			
			if( $names_str!="" )
			{
				$DB->Execute( "INSERT INTO $table ($names_str) VALUES ($values_str)" );
			}
		}
	}	
	
	function restoreData( $fromtable, $table )
	{
		global $DB;
//		$DB->debug=true;
		
		$this->saveTableInfo( "ann_$table",array() );
		$this->saveTableInfo( "data",      array("search_vec") );

		$this->restore( "data",        $this->myData["data"] );
		$this->restore( "ann_".$table, $this->myData[$fromtable] );
	}
	
	function reCreateTable( $table,$type )
	{
		global $DB;
		$DB->debug=true;

		$DB->Execute( "BEGIN" );

		$save_ok=$this->saveData( $table );
		
		$DB->Execute( "DROP TRIGGER tr_data_ondelete ON data" );
		$DB->Execute( "DELETE FROM data WHERE cat_id IN (select * FROM generate_ids_type('$table'))" );
		$DB->Execute( "CREATE TRIGGER tr_data_ondelete
  BEFORE DELETE
  ON data
  FOR EACH ROW
  EXECUTE PROCEDURE tr_data_remove_photos();
" );
		
		$attrs=$DB->Execute( "SELECT * FROM attrs WHERE attr_type_id='$type'" );
		
		$DB->Execute( "DROP TABLE IF EXISTS ann_$table" );
		$sql="CREATE TABLE ann_$table 
			( id INTEGER NOT NULL";
		foreach( $attrs as $attr )
		{
			switch( $attr['attr_type'] )
			{
				case 4:
					$count=(isset($attr['attr_options2'])&&is_numeric($attr['attr_options2']))?$attr['attr_options2']:10;
					for( $i=0; $i<10; $i++ )
					{
						$sql.=", ".$attr['attr_name']."$i ".$this->TYPES[$attr['attr_type']];
					}
					break;
				case 1: case 2: case 3: case 6: case 7: case 8: case 10: case 11: case 12: case 17:
					$sql.=", ".$attr['attr_name']." ".$this->TYPES[$attr['attr_type']];
					break;
				case 5:
					$sql.=", ".$attr['attr_name']." ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_cur ".$this->TYPES[$attr['attr_type']];
					break;
				case 16:
					$sql.=", ".$attr['attr_name']." ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_cur ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_extra integer";
					break;
				case 18:
					$sql.=", ".$attr['attr_name']." ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_cur ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_torg boolean";
					break;
				case 9:
				case 19:
					$arr=split( ",",$attr['attr_options'] );
					foreach( $arr as $field )
					{
						$sql.=", ".$attr['attr_name']."_".$field." ".$this->TYPES[$attr['attr_type']];
					}
					break;
				case 15:
					$sql.=", ".$attr['attr_name']."_num ".$this->TYPES[$attr['attr_type']];
					$sql.=", ".$attr['attr_name']."_code integer";
					break;
				case 13: case 14:
				default:
					break; //do nothing, unknown type
			}
		}
		
		$sql.=", CONSTRAINT ann_".$table."_pkey PRIMARY KEY (id),
				 CONSTRAINT fk_ann_".$table."_id FOREIGN KEY (id)
  					REFERENCES data (id) MATCH SIMPLE
  					ON UPDATE CASCADE ON DELETE CASCADE
		)";
		$DB->Execute( $sql );
		$DB->Execute( "SELECT recald_all_product_num()" );

		if( $save_ok ) $this->restoreData( $table,$table );

		$DB->Execute( "COMMIT" );
		$DB->Execute( "VACUUM ANALYZE" );
		die;
	}
}
