<?php

class CreateOrReplaceTableColumn extends BaseColumn 
{
	var $TYPES=array( 1=>"text",2=>"integer",3=>"text",4=>"text",5=>"integer",6=>"text",7=>"text",8=>"boolean",
					  9=>"boolean",10=>"integer",11=>"integer",12=>"numeric", 15=>"numeric",16=>"integer",
					  17=>"text",18=>"integer",19=>"boolean",20=>"text" );
	
	function __construct( $name,$descr )
	{
		parent::__construct( $name,$descr,true,false,false);
		$this->mySQL=false;
	}
	
	function getInsert( &$request )
	{
	}
	
	function saveData( $table )
	{
		global $DB;
		
		$res=$DB->Execute( "select column_name from INFORMATION_SCHEMA.columns where table_name='ann_$table'");
		$cols=array();
		foreach( $res as $r ) array_push( $cols,$r['column_name'] );
		if( sizeof($cols)==0 ) return false;

		$res=$DB->Execute( "SELECT * FROM data WHERE cat_id IN (SELECT cat_id FROM catalog WHERE cat_type=(SELECT type_id FROM cat_types WHERE type_data='$table'))" );
		$this->myMainData=$res->GetRows( );
		
		$res=$DB->Execute( "SELECT * FROM ann_$table" );
		$this->myData=$res->GetRows( );
		
		return true;
	}
	
	function restore( $table, &$cols, &$data )
	{
		global $DB;
		
		foreach( $data as &$val )
		{
			$names=array();
			$values=array();
			
			foreach( $cols as $col )
			{
				if( isset($val[$col]) )
				{
					array_push( $names, $col );
//					$vvv=str_replace( "'", "\\'", $val[$col] );
//					array_push( $values, "'$vvv'" );
					array_push( $values, $DB->qstr($val[$col]) );
				}
			}

			$names_str=implode( ",",$names );
			$values_str=implode( ",",$values );
			
			if( $names_str!="" )
			{
//				print "INSERT INTO $table ($names_str) VALUES ($values_str);\n";
				$DB->Execute( "INSERT INTO $table ($names_str) VALUES ($values_str)" );
			}
			else {
				print "No values found for row<br/>\n";
				die;
			}
		}		
	}
	
	function restoreData( $table )
	{
		global $DB;
		$DB->debug=true;

		$res=$DB->Execute( "select column_name from INFORMATION_SCHEMA.columns where table_name='ann_$table'");
		$cols=array();
		foreach( $res as $r )  
		{
//			if( $r['column_name']!='complect_phone' && 
//				$r['column_name']!='complect_tv' && 
//				$r['column_name']!='complect_internet' &&
//				$r['column_name']!='complect_ohran' ) 
//			{
				array_push( $cols,$r['column_name'] );
//			}
		}
		
		$res=$DB->Execute( "select column_name from INFORMATION_SCHEMA.columns where table_name='data'");
		$main_cols=array();
		foreach( $res as $r ) { if( $r['column_name']!='search_vec' ) array_push( $main_cols,$r['column_name'] ); }

		$this->restore( "data", $main_cols, $this->myMainData );
		$this->restore( "ann_".$table, $cols, $this->myData );
	}
	

	function getUpdate( &$request )
	{
		global $DB;
		$DB->debug=true;
		if( isset($request["cr$this->myName"]))
		{
			$DB->Execute( "BEGIN" );

			$save_ok=$this->saveData( $request[$this->myName] );
//			$this->restoreData( $request[$this->myName] );
//			die;

			$DB->Execute( "DROP TRIGGER tr_data_ondelete ON data" );
			$DB->Execute( "DELETE FROM data WHERE cat_id IN (select * FROM generate_ids_type('".$request[$this->myName]."'))" );
			$DB->Execute( "CREATE TRIGGER tr_data_ondelete
  BEFORE DELETE
  ON data
  FOR EACH ROW
  EXECUTE PROCEDURE tr_data_remove_photos();
" );
			
			$attrs=$DB->Execute( "SELECT * FROM attrs WHERE attr_type_id='".$request['type_id']."'" );
			$DB->Execute( "DROP TABLE IF EXISTS ann_".$request[$this->myName] );
			$sql="CREATE TABLE ann_".$request[$this->myName]." 
				( id INTEGER NOT NULL";
			foreach( $attrs as $attr )
			{
				switch( $attr['attr_type'] )
				{
					case 4:
					case 20:
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
			
			$sql.=", CONSTRAINT ann_".$request[$this->myName]."_pkey PRIMARY KEY (id),
					 CONSTRAINT fk_ann_".$request[$this->myName]."_id FOREIGN KEY (id)
      					REFERENCES data (id) MATCH SIMPLE
      					ON UPDATE CASCADE ON DELETE CASCADE
			)";
			$DB->Execute( $sql );
//			$DB->Execute( "SELECT recald_all_product_num()" );

			if( $save_ok ) $this->restoreData( $request[$this->myName] );

			$DB->Execute( "COMMIT" );
//			$DB->Execute( "VACUUM ANALYZE" );
			die;
		}
	}

	function getInput( &$row )
	{
		return "<input type='checkbox' name='cr$this->myName' />";
	}
}

