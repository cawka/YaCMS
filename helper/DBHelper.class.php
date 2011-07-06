<?php

class DBHelper
{
	public static function connect()
	{
		global $DB;
		global $dbengine, $dbuser, $dbpasswd, $dbhost, $dbschema;

		if( !$DB->IsConnected() )
		{
			$DB->Connect( $dbhost, $dbuser, $dbpasswd, $dbschema );
		}	
	}
}

