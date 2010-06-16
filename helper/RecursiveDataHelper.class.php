<?php

class RecursiveDataHelper
{
	var $myTableName=''; //need to be configured
	var $myId;
	var $myExtraWhere;
	var $myRecursiveId;
	var $myOrder;

	var $myData;
	var $myInfo;
	
	/**
	 * Link to parent item
	 *
	 * @var Menu
	 */
	var $myParent;
	var $myName;

	var $mySelectedRow;
	
	function __construct( $selected,&$parentobj,&$inforow,$parentid=NULL,$table,$id="id",$extra="",$recur_id="",$order="myorder",$name="title" )
	{
		global $DB;
		
		$this->myTableName=$table;
		$this->myId=$id;
		$this->myExtraWhere=$extra;
		$this->myRecursiveId=($recur_id!=""?$recur_id:"$table"."_id");
		$this->myOrder=$order;
		$this->myName=$name;
		
		if( $parentobj->Fake!=true ) $this->myParent=&$parentobj;
		if( $inforow->Fake!=true ) $this->myInfo=&$inforow;
		if( $parentid===null ) 
			$q=" IS NULL "; 
		else
		{ 
			$q=" = '$parentid' ";
		}
//		if( $parentobj->Fake==true )
			$this->myTitle=$DB->GetOne( "SELECT $name FROM $this->myTableName WHERE $this->myExtraWhere $this->myId $q" );
		
		$res=$DB->Execute( "SELECT * FROM $this->myTableName WHERE $this->myExtraWhere $this->myRecursiveId $q ORDER BY $this->myOrder" );
		$this->myData=&$res->GetRows( );
		
		
		if( $parentobj->Fake==true && sizeof($this->myData)==0 ) 
		{
			$this->myData=array($DB->GetRow("SELECT * FROM $this->myTableName WHERE $this->myExtraWhere $this->myId $q"));
		}
		
		foreach( $this->myData as $key=>$value ) 
		{
			$row=&$this->myData[$key];
			$row['child']=&new RecursiveData( $selected,$this,$row,$row[$this->myId],
					$this->myTableName,$this->myId,$this->myExtraWhere,$this->myRecursiveId,$this->myOrder,$this->myName );
			if( !$row['child']->myData || sizeof($row['child']->myData)==0 ) $row['child']=null;
			
			if( $selected==$row[$this->myId] )
			{
				$row['isselected']=true;
			
				$level=&$this;
				while( isset($level) )
				{
					if( isset($level->myInfo) ) 
					{
						$level->myInfo['isselected']=true;
					}

					if( $level->myParent===null )
					{
						$level->mySelectedRow=$row;
					}
					$level=&$level->myParent;
				}
			}

		}
	}
	
	function getSelectedSubmenu( )
	{
		foreach( $this->myData as $key=>$value )
		{
		    $item=&$this->myData[$key];
			if( $item['isselected']==true ) return $item['child'];
		}
		return NULL;
	}
	
	function getLink( $id )
	{
		//need to be overriden
	}
	
	function canShowTitles( )
	{
		return sizeof($this->myData)<2;
	}
}


?>
