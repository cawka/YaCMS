<?php

class CatalogsHelper extends BaseTableThickBoxHelper
{
	public function getInfo( $cat_id )
	{
		global $DB,$LANG;

		if( isset($cat_id) && trim($cat_id)!="" )
		{
			$ret=APC_GetRow( array("catalog-info",$LANG,$cat_id),$DB,
				"SELECT *,subpath(cat_tree,0,1) as cat_section,nlevel(cat_tree) as cat_level
					FROM catalog_lang$LANG
					WHERE cat_id=".$DB->qstr($cat_id), 0 );
		}
		else
		{
			$ret=array( "cat_template"=>"index.tpl", "type_levels"=>2, "cat_level"=>0 );
		}

		return $ret;
	}

	static public function getParentPath( $cat_id, $cat_tree=NULL )
	{
		global $DB,$LANG;
		if( !isset($cat_id) || trim($cat_id)=="" ) return array();
		if( !isset($cat_tree) )
		{
			$info=CatalogsHelper::getInfo( $cat_id );
			$cat_tree=$info['cat_tree'];
		}

		$path=preg_split( "/\./", $cat_tree ); //nlevel()

		return APC_GetRows( array("catalog-parent",$LANG,$cat_id), $DB,
			"SELECT * FROM catalog_lang$LANG
				WHERE cat_id in (".implode(",", $path).")
				ORDER BY nlevel(cat_tree)", 0 );
	}

	static public function getSubitems( $cat_id )
	{
		global $DB;

		$ret=APC_GetRow( array("catalog-subitems",$cat_id),$DB,
				"SELECT count(*) as count FROM catalog WHERE cat_cat_id=".$DB->qstr($cat_id), 0 );

		return $ret['count'];
	}

	static public function printPath( $cat_id, $glue=", ", $limited=NULL )
	{
		if( !isset($cat_id) ) return "";

		$info=CatalogsHelper::getInfo( $cat_id );
		$path=CatalogsHelper::getParentPath( $cat_id, $info['cat_tree'] );
		$ret=array();
		foreach( $path as &$value ) $ret[]=$value['cat_name'];

		if( isset($limited) ) $ret=array_slice( $ret, $limited );
		return implode( $glue, $ret );
	}

	static public function printPathWithLink( $cat_id, $glue=" >> ", $limited=NULL, $globalLink=false )
	{
		global $SITEURL;

		if( !isset($cat_id) ) return "";

		$info=CatalogsHelper::getInfo( $cat_id );
		$path=CatalogsHelper::getParentPath( $cat_id, $info['cat_tree'] );
		$ret=array();

		$prefix="";
		if( $globalLink ) $prefix=$SITEURL;
		foreach( $path as &$value )
		{
			$ret[]="<a target='_blank' href='$prefix/catalog-$value[cat_id].html'>".$value['cat_name']."</a>";
		}

		if( isset($limited) ) $ret=array_slice( $ret, $limited );
		return implode( $glue, $ret );
	}

	static public function printPathPreprepared( $path_str, $path_ids_str, $delim=" >> ", $strip="" )
	{
		$path=explode( ">>",$path_str );
		$path_id=explode( ".",$path_ids_str );

		if( sizeof($path)!=sizeof($path_id) ) return implode( $delim,$path );
		$path_new=array();

		for( $i=sizeof($path)-1; $i>0; $i-- )
		{
			if( $path_id[$i]==$strip ) break;
			array_push( $path_new, $path[$i] );
		}
		return implode( $delim,array_reverse($path_new) );
	}


	static public function getOptions( $cat_id )
	{
		global $DB,$LANG;
//		$DB->debug=true;

		$sql= "SELECT cat_id,cat_name FROM catalog_lang$LANG WHERE cat_cat_id ";
		if( !isset($cat_id) || $cat_od=="" )
			$sql.=" IS NULL ";
		else
			$sql.="=".$DB->qstr( $cat_id );

		$sql.=" ORDER BY cat_order";

		return APC_GetAssoc( array("catalogs",$LANG,$cat,'assoc'),$DB,$sql,0 );
	}
	

	static public function redirectIfContainsSubcatalogs( $cat_id, $info )
	{
		global $DB;
		if( !isset($cat_id) ) return;
		if( !($info['cat_template']=='realestate.tpl' ||
			$info['cat_template']=='realestate_nomap.tpl') ) return;
//		$DB->debug=true;

		$cat_id=addslashes( $cat_id );
		
		$row=APC_GetRow( array("catalog_redirect",$cat_id), $DB,
			"SELECT cat_id,cat_template FROM catalog_lang1 ".
				"WHERE cat_cat_id='$cat_id'".
					" AND nlevel(cat_tree)=(SELECT max(nlevel(cat_tree)) ".
												"FROM catalog where cat_tree ~ '*.$cat_id.*') ".
				"ORDER BY cat_order_ltree LIMIT 1", 0 );
		if( sizeof($row)>0 )
		{
			ErrorHelper::redirect( "/list-$row[cat_id].html" );
		}
	}

	static public function redirectIfAdsList( $cat_id, $info )
	{
		if( $info['cat_template']=="realestate.tpl" ||
			$info['cat_template']=="realestate_nomap.tpl" )
			ErrorHelper::redirect( "/list-$cat_id.html" );
	}

	static public function getLevelName( $cat_id, $level )
	{
		global $langdata;
		return $langdata['post_cat_select'];

//		switch( $level )
//		{
//		case 0:
//			return $langdata['search_show_all'];
//		default:
//			return "";
//		}
//
	}
}
