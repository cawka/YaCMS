<?php

class GroupTableOutputColumn extends GroupColumn
{
	var $myColCount;
	var $myMakeHidden=false;

 	function __construct( $name,$descr,$columns,$visible,$req,$brief,$outpcolcount=4,$hidden=false )
 	{
 		$this->myColCount=$outpcolcount;
 		$this->myMakeHidden=($hidden=="hidden");
 		parent::__construct( $name,$descr,$columns,$visible,$req,$brief );
 	}

	function getInput( &$row )
	{
		$ret="";
		if( $this->myMakeHidden )
		{
			$ret.="<a href='javascript:;' onclick='trigger(\"$this->myName\")'><img id='button_$this->myName' src='/images/plus_9_px.gif' class='collapsed'/></a>";
			$ret.="<div id='group_$this->myName' class='collapsed'>";
		}
		$ret.="<table class='boolgroup'><tr>";
		$slice_count=floor(sizeof( $this->myColumns )/$this->myColCount+$this->myColCount-1);
		$width=round(100/$this->myColCount);

		$i=0;
		foreach( $this->myColumns as $col )
		{
			if( $i%$this->myColCount==0 )
			{
				if( $ret!="" ) $ret.="</tr>";
				$ret.="<tr>";
			}
			$ret.="<td width='$width%'>".$col->myDescription."&nbsp;".$col->getInput( $row )."</td>";
			$i++;
		}
		$ret.="</tr></table>";
		if( $this->myMakeHidden )
		{
			$ret.="</div>";
		}
		return $ret;
	}
}
