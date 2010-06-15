<?php

class CatalogColumn extends ListDBColumn 
{
	var $myLevels;
	
	function __construct( $name,$descr,$required,$levels=3,$parent_path=true )
	{
		global $LANG;
		$this->myLevels=$levels;
		
		parent::__construct( $name,$descr,$required, "catalog_lang$LANG",
			"cat_id","cat_name" );

		$this->myWhere=array( "cat_cat_id"=>NULL ); //by default, only top level catalog
		$this->myOrder="cat_order,cat_name";
		$this->buildParentPath=$parent_path;
	}

	function getInput( &$request )
	{
		global $langdata,$DB;
		$ret=""; $divcount=0;

		if( $this->buildParentPath )
		{
			$parentPath=CatalogsHelper::getParentPath( $request["cat_id"] );

			if( sizeof($parentPath)<$this->myLevels )
			{
				if( sizeof($parentPath)>0 )
					$rcat=$parentPath[ sizeof($parentPath)-1 ]['cat_id'];
				else
					$rcat=NULL;

				$parentPath[]=array( "cat_cat_id"=>$rcat );
			}
		}
		else
			$parentPath=array( $request );

		$class=$this->myClass;
		if( isset($this->myRequired) ) $this->myClass.=" validate-custom-required emptyValue:'' ";

		$i=0;
		foreach( $parentPath as $val )
		{
			$info=CatalogsHelper::getInfo( $val['cat_cat_id'] );
			$level=isset($info['cat_level'])?$info['cat_level']:0;

			$this->myWhere["cat_cat_id"]=$val['cat_cat_id'];
			$this->myFirstElements=array( $val['cat_cat_id']=>
				CatalogsHelper::getLevelName($val['cat_cat_id'],$level) );

			if( $this->myLevels<0 || !isset($level) || $level<$this->myLevels-1 )
				$this->myExtraStuff=" onchange=\"getCatalog(this,'".$val['cat_cat_id']."',$this->myLevels)\"";
			else
				$this->myExtraStuff="";

			if( $i>0 ) $this->myClass=$class;

			$ret.=parent::getInput( $val );

			$ret.="<div id='rcatalog".$val['cat_cat_id']."'>";
			$divcount++;
			$i++;
		}

		for( $i=0; $i<$divcount; $i++ ) $ret.="</div>";
		return $ret;
	}

//	function getInput( &$row )
//	{
//		global $langdata,$DB;
//
//		$ret=""; $divcount=0;
//
//		$parentPath=CatalogsHelper::getParentPath( $row[$this->myKey] );
//
//		if( sizeof($parentPath)<$this->myLevels )
//		{
//			if( sizeof($parentPath)>0 )
//				$rcat=$parentPath[ sizeof($parentPath)-1 ]['cat_id'];
//			else
//				$rcat=NULL;
//
//			$parentPath[]=array( "cat_cat_id"=>$rcat );
//		}
//
//		$class=$this->myClass;
//		if( isset($this->myRequired) ) $this->myClass.=" validate-custom-required emptyValue:'' ";
//
//		$i=0;
//		$level=$this->myLevels;
//		foreach( $parentPath as $val )
//		{
////			$dop=isset($val['cat_cat_id'])?"='".$val['cat_cat_id']."' ":" IS NULL ";
////			$this->myOptions=CatalogsHelper::getOptions( $val['cat_cat_id'] );
//////			$DB->GetAssoc( "SELECT $this->myKey,$this->myVal FROM $this->myTblName $this->myWhere AND cat_id IS NOT NULL AND cat_cat_id$dop ORDER BY $this->myOrder" );
////			if( sizeof($this->myOptions)==0 ) break;
////
////			$ret.="<select class='addann_select' name='$this->myName' ";
////			if( $level>=2 ) $ret.="onchange=\"getCatalog(this,'".$val['cat_cat_id']."',$level)\"";
////			$ret.=">\n";
////			$ret.="<option value='".$val['cat_cat_id']."'>";
////			$ret.=(( $dop!=" IS NULL " )?$langdata['post_cat_select']:"");//$langdata['cat_all_ukraine']);
////			//$ret.=$langdata["cat_all_$i"];
////			$ret.="</option>\n";
////			foreach( $this->myOptions AS $key=>$value )
////			{
////				$ret.="<option value='$key'".(($key==$val['cat_id'])?" selected='selected'":"").">$value</option>\n";
////			}
////			$ret.="</select>\n";
//
//			$this->myOptions=CatalogsHelper::getOptions( $val['cat_cat_id'] );
//
//			if( sizeof($this->myOptions)==0 ) break;
//
//			if( $level>=2 )
//				$this->myExtraStuff=" onchange=\"getCatalog(this,'".$val['cat_cat_id']."',$level)\"";
//			else
//				$this->myExtraStuff="";
//
//			if( $i>0 ) $this->myClass=$class;
//
//			$ret.=parent::getInput( $val );
//
//			$ret.="<div id='rcatalog".$val['cat_cat_id']."'>";
//			$divcount++;
//			$i++;
//			$level--;
//		}
//
//		for( $i=0; $i<$divcount; $i++ ) $ret.="</div>";
//		return $ret;
//	}

	function extractValue( &$row )
	{
		return CatalogsHelper::printPath( $row[$this->myKey] );
	}

	function extractBriefValue( &$row )
	{
		return $this->extractOnlyValue( $row,NULL );
	}
	
	function extractOnlyValue( &$row, $levels=4 )
	{
		return CatalogsHelper::printPath( $row[$this->myKey], "<br/> ", $levels );
	}
}
