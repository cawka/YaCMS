<?php

class UrlHelper
{
	static public function getGetRequest( $params )
	{
		$good=array();
		foreach( $params as $key=>$value )
		{
			if( isset($value) && $value!="" ) $good[]="$key=$value";
		}
		$req=implode( "&amp;", $good );
		
		if( $req!="" ) $req="?".$req;
		return $req;
	}

	static public function changeURI( $new_base )
	{
		$url=$_SERVER['REQUEST_URI'];
		
		$get=preg_replace( "/^.*\?/", "", $url );
		if( $get=="" ) return $new_base;

		return $new_base."?".$get;
	}
}
