<?php

require_once "Net/GeoIP.php";

class GeoHelper
{
	private $_geo;

	public function __construct( )
	{
		$this->_geo = Net_GeoIP::getInstance( CMSDIR. "/lib/geo/GeoLiteCity.dat" );
	}

	public function locate( $ip )
	{
		try
		{
			$loc=$this->_geo->lookupLocation( $ip );
			$x=array();
			if( $loc->city!="" ) $x[]=$loc->city;
			if( $loc->countryName!="" ) $x[]=$loc->countryName;

			return implode( ", ", $x );
		}
		catch( Exception $e )
		{
			return "";
		}
	}

}

