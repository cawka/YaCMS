<?php

class RegionsColumn extends ListDBColumn 
{
	var $myLevels;
	public $blankLevelZero=true;
	
	function __construct( $name,$descr,$required,$levels=3,$parent_path=true )
	{
		global $LANG;
		$this->myLevels=$levels;
		
		parent::__construct( $name,$descr,$required, "regions_lang$LANG",
			"reg_id","reg_name" );

		$this->myWhere=array( "reg_reg_id"=>NULL ); //by default, only top level regions
		$this->myOrder="reg_order,reg_name";
		$this->buildParentPath=$parent_path;
	}

	function getInput( &$request )
	{
		global $langdata,$DB;
		$ret=""; $divcount=0;

		if( $this->buildParentPath )
		{
			$parentPath=RegionsHelper::getParentPath( $request["reg_id"] );

//			if( $this->myLevels<0 || sizeof($parentPath)<$this->myLevels )
//			{
				if( sizeof($parentPath)>0 )
					$rreg=$parentPath[ sizeof($parentPath)-1 ]['reg_id'];
				else
					$rreg=NULL;

				$parentPath[]=array( "reg_reg_id"=>$rreg );
//			}
		}
		else
			$parentPath=array( $request );

		$class=$this->myClass;
		if( isset($this->myRequired) ) $this->myClass.=" validate-custom-required emptyValue:\"\" ";

		$i=0;
		foreach( $parentPath as $val )
		{
			$info=RegionsHelper::getInfo( $val['reg_reg_id'] );
			$level=isset($info['reg_level'])?$info['reg_level']:0;

			$this->myWhere["reg_reg_id"]=$val['reg_reg_id'];
			$this->myFirstElements=array( $val['reg_reg_id']=>
				RegionsHelper::getLevelName($val['reg_reg_id'],$level,$this->blankLevelZero) );

//			if( $this->myLevels<0 || !isset($level) || $level<$this->myLevels-1 )
			$this->myExtraStuff=" onchange=\"getRegions(this,'".$val['reg_reg_id']."',$this->myLevels)\"";
//			else
//				$this->myExtraStuff="";

			if( $i>0 ) $this->myClass=$class;

			$ret.=parent::getInput( $val );

			$ret.="<div id='rregion".$val['reg_reg_id']."'>";
			$divcount++;
			$i++;

			if( $this->myLevels>0 && $i>=$this->myLevels )
			{
				break;
			}
		}
		
		for( $i=0; $i<$divcount; $i++ ) $ret.="</div>";
		return $ret;
	}

	public function checkBeforeSave( &$row )
	{
		$info=RegionsHelper::getInfo( $row[$this->myName] );
		return sizeof($info)>0;
	}

	function extractValue( &$row )
	{
		return RegionsHelper::printPath( $row[$this->myKey] );
	}
	
	function extractBriefValue( &$row )
	{
		return $this->extractOnlyValue( $row,NULL );
	}
	
	function extractOnlyValue( &$row, $levels=4 )
	{
		return RegionsHelper::printPath( $row[$this->myKey], "<br/> ", $levels );
	}
}
