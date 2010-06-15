<?php

class UserLogoColumn extends UserPhotoColumn 
{
	function extractValue( &$row,$path="" )
	{
		$ret="<div style='text-align: left'>";
		for( $i=0; $i<$this->myCount; $i++ ) 
		{
			if( $row[$this->getName($i)]=="" ) continue;
			$ret.=  "<img src='".$this->getPath($i,"140x104",$row)."' class='ulogo_th' />";
		}
		$ret.="</div>";
		return $ret;
	}	
}
