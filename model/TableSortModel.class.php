<?php

class TableSortModel extends TableBaseModel
{
	public $mySortColumns=array();

	public function __construct( $php, $offsets=false )
	{
		parent::__construct( $php, $offsets );
		
	}

	public function getSortHeaderLink( $colname )
	{
		if( isset($this->mySortColumns[$colname]) )
		{
			$set="sort=".$this->myColumns[$colname]->getSortName();
			if( $_REQUEST['sort']==$this->myColumns[$colname]->getSortName() && !isset($_REQUEST['desc']) ) $set.="&amp;desc";

			$ret="<a href='".$this->myHelper->getRequest( array("sort","desc"), $set )."'>";

			if( $this->myColumns[$colname]->myBriefMsg!="" )
				$ret.=$this->myColumns[$colname]->myBriefMsg;
			else
				$ret.=$this->myColumns[$colname]->myDescription;
			$ret.="</a>";
			return $ret;
		}
		else
			return ($this->myColumns[$colname]->myBriefMsg!="")?$this->myColumns[$colname]->myBriefMsg:$this->myColumns[$colname]->myDescription;
	}

	public function getSortHeaderLinkNewStyle( $colname,$title )
	{
		if( isset($this->mySortColumns[$colname]) )
		{
			$set="sort=$colname";
			if( $_REQUEST['sort']=="$colname" && !isset($_REQUEST['desc']) ) $set.="&amp;desc";

			$ret="<a href='".$this->myHelper->getRequest(array("_m","sort","desc","ajax"),$set)."'>";
			$ret.="$title</a>";

			if( $_REQUEST['sort']=="$colname" )
			{
				$ret.=' <img  border="0" width="10" height="10" src="/images/';
				if( !isset($_REQUEST['desc']) )
					$ret.="up.gif\" />";
				else
					$ret.="down.gif\" />";
			}
			return $ret;
		}
		else 
			return $title;
	}
	
	
	public function getSortOptions( )
	{
		global $langdata;
	
//		return "pp".sizeof( $this->mySortColumns);
		if( sizeof($this->mySortColumns)==0 ) return "";
		$ret.="<span class=\"sortingblock\">".$langdata["sort_by"]; 
		$ret.=" <select class=\"sortingselect\" onchange=\"document.location.href='".$_SERVER['PHP_SELF']."?".getRequest(array("skip"=>"sort"))."sort='+this.options[this.selectedIndex].value\">\n";
		foreach( $this->mySortColumns as $c )
		{
			$ret.="<option value='".$this->myColumns[$c]->getSortName()."' ";
			if( $_REQUEST['sort']==$this->myColumns[$c]->getSortName() ) $ret.=" selected='selected' ";
			$ret.=">".$this->myColumns[$c]->mySortName."</option>\n";
		}
		$ret.="</select>\n</span>\n";
		return $ret;
	}
}
