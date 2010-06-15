<?php

class PhotoLangColumn extends BaseColumn 
{
	function getUpdateName( )
	{
		return $this->myName.($this->myIsFromView?"_id":"");
	}
	
	function getInsert( &$request )
	{
		$id=saveTextData( $request[$this->myName], $this->myName );
		return "'$id'";
	}
	
	function getValue( &$row )
	{
		global $LANG;
		if( isAdmin() ) //return value in all languages
		{
			return label_box( array("id"=>$row[$this->myName]) );
		}
		else 
		{
			return $row[$this->myName];
		}
	}
	
	function getInputHidden( $row )
	{
		$ret="";
		if( isset($row[$this->myName]) )
		{
			$ret="<input class=\"i\" type='hidden' name='".$this->myName."' value='".$row[$this->getUpdateName()]."' />";
		}		
		return $ret;			
	}
	
	function getInput( &$row )
	{
		$ret=photo_box( array("name"=>$this->myName,"id"=>$row[$this->getUpdateName()],
								"required"=>$this->myRequired) );
		return $ret.$this->getInputHidden( $row );
	}
}
