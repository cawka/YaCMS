<?php

class IPHelper
{
	static public function getIP( )
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if( preg_match("/^::ffff:(.+)$/",$ip,$matches) ) // IPv4-mapped IPv6 address
		{ 
			$ip = $matches[1];
		}

		return $ip;
	}
}

