<?php

class EmailColumn extends  TextColumn 
{
	function __construct( $name,$descr,$isreq,$required=NULL,$brief=false,$brmsg="" )
	{
		parent::__construct( $name,$descr,$isreq?$required:NULL,$brief,$brmsg,"","",false,"",255 );
				
		array_push( $this->myValidate, "validate-email" );
	}

	function extractValue( &$row )
	{
		global $langdata;
		
		if( !isAdmin() )
		{
			if( $row[$this->myName]!="" )
				return "<a href='/tools/user2user-".$row['id']."-".$this->myName."' target='_blank'>".$langdata['user_sendmessage']."</a>";
			else 
				return "";
		}
		else 
			return $row[$this->myName];
	}
	
	function extractAdminValue( &$row )
	{
		return parent::extractValue( $row );
	}

	function extractXMLValue( $row )
	{
		return "hidden";
	}
}

?>
