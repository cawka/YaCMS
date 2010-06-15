<?php

class RegionsHelper extends BaseHelper
{
	static public function getInfo( $reg_id )
	{
		global $DB,$LANG;

		if( isset($reg_id) )
		{
			$ret=APC_GetRow( array("region-info",$LANG,$reg_id),$DB,
				"SELECT *,nlevel(reg_tree) as reg_level ".
				"FROM regions_lang$LANG WHERE reg_id=".$DB->qstr($reg_id),0 );
		}
		else
		{
			$ret=array( );
		}

		return $ret;
	}

	static public function getParentPath( $reg_id, $reg_tree=NULL )
	{
		global $DB,$LANG;

		if( !isset($reg_id) || trim($reg_id)=="" ) return array();
		if( !isset($reg_tree) )
		{
			$info=RegionsHelper::getInfo( $reg_id );
			$reg_tree=$info['reg_tree'];
		}

		$path=preg_split( "/\./", $reg_tree ); //nlevel()

		return APC_GetRows( array("regions-parent",$LANG,$reg_id), $DB,
			"SELECT * FROM regions_lang$LANG
				WHERE reg_id in (".implode(",", $path).")
				ORDER BY nlevel(reg_tree)",0 );
	}

	static public function printPath( $reg_id, $glue=", ", $limited=NULL )
	{
		if( !isset($reg_id) ) return "";

		$info=RegionsHelper::getInfo( $reg_id );
		$path=RegionsHelper::getParentPath( $reg_id, $info['reg_tree'] );
		$ret=array();
		foreach( $path as &$value ) $ret[]=$value['reg_name'];
		
		if( isset($limited) ) $ret=array_slice( $ret, $limited );
		return implode( $glue, $ret );
	}

//	static public function getOptions( $reg_id )
//	{
//		global $DB,$LANG;
//
//		$sql="SELECT reg_id,reg_name FROM regions_lang$LANG WHERE reg_reg_id ";
//		if( !isset($reg_id) || $reg_od=="" )
//			$sql.=" IS NULL ";
//		else
//			$sql.="=".$DB->qstr( $reg_id );
//
//		$sql.=" ORDER BY reg_order ";
//
//		return APC_GetAssoc( array("regions",$LANG,$reg_id,'assoc'),$DB,$sql,0 );
//	}

	static public function getLevelName( $reg_id,$level,$blankLevelZero )
	{
		global $langdata;

		switch( $level )
		{
		case NULL:
			if( $blankLevelZero )
				return "";
			else
				return $langdata['reg_all_oblasti'];
			break;
		case 1:
			//$reg.reg_id!=26 && $reg.reg_id!=28
			if( $reg_id==26 || $reg_id==28 ) return $langdata['reg_all_1'];
			return $langdata['reg_all_0'];
			break;
		case 2:
			$info=RegionsHelper::getInfo( $reg_id );
			$reg_id=$info['reg_reg_id'];
			if( $reg_id==26 || $reg_id==28 ) return $langdata['reg_all_rayon'];
			return $langdata['reg_all_1'];
		default:
			return $langdata['post_cat_select'];
			break;
		}
	}
}
