<?php

//require_once "Net/GeoIP.php";

class GeoHelper
{
//	private $_geo;
//
//	public function __construct( )
//	{
//		$this->_geo = Net_GeoIP::getInstance( CMSDIR. "/lib/geo/GeoLiteCity.dat" );
//	}
//
//	public function locate( $ip )
//	{
//		try
//		{
//			$loc=$this->_geo->lookupLocation( $ip );
//			$x=array();
//			if( $loc->city!="" ) $x[]=$loc->city;
//			if( $loc->countryName!="" ) $x[]=$loc->countryName;
//
//			return implode( ", ", $x );
//		}
//		catch( Exception $e )
//		{
//			return "";
//		}
//	}

	static $_geoCodes;

	public static function codeToName( $code )
	{
		global $DB;
		if( !isset($_codes) )
		{
			$_geoCodes=APC_GetAssoc( array("geoCodes"), $DB, "SELECT ISO2,NAME FROM countries", 0 );
		}

		return $_geoCodes[$code];
	}
}

