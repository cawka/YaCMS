<?php

class StaticPagesHelper extends BaseTableThickBoxHelper 
{
	public static function updateLastModifiedStatic( $static_id )
	{
		global $DB;

	    if( !isset($static_id) ) return;
	    $DB->Execute( "UPDATE static_pages SET lastmodified=NOW() WHERE id=".$DB->qstr($static_id) );
	}
}

