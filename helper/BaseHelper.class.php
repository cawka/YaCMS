<?php

class BaseHelper
{
	public function clearCache( )
	{
		global $theAPC;
		$theAPC->clear_all( );
	}

	public function clearCachePath( $path )
	{
		global $LANGS;

		foreach( $LANGS as $key=>$value )
		{
			$dir=TEMPDIR . "cache/" . $key . "/" . implode("/",$path);
			system( "rm -Rf $dir" );
		}
	}

	static public function getRequest( $clear="", $set="" )
	{
		$url=$_SERVER['REQUEST_URI'];
		if( $clear!="" )
		{
			if( !is_array( $clear) ) $clear=array($clear);
			foreach( $clear as $value )
			{
				while( true )
				{
					$url1=preg_replace( array("/$value=?([^&])*/","/\?$/","/&$/","/\?&/","/&&/"),
									   array("", "","","?","&"), $url );
					if( $url1==$url ) break;
					$url=$url1;
				}
			}
		}

		$url=preg_replace( "/&/", "&amp;", $url );

		if( $set!="" )
		{
			if( strstr($url,"?") ) $url.="&amp;"; else $url.="?";
			$url.=$set;
		}

		return $url;
	}

	static public function getBaseRequest( )
	{
		$url=$_SERVER['REQUEST_URI'];
		return preg_replace( "/\?.*$/", "", $url );
	}

	static public function getGETasInputHidden( $skip=array(),$limit=20 )
	{
		$url=$_SERVER['REQUEST_URI'];
		if( !preg_match("/\?/",$url) ) return "";

		$get=preg_replace( "/^.*\?/", "", $url );
		if( $get=="" ) return "";

		$get=preg_split( "/&/", $get );

		$ret="";
		$hash=array(); foreach( $skip as $value ) {  $hash[$value]=1;  }

		foreach( $get as $req )
		{
			$kk=preg_split( "/=/", $req );
			
			if( isset($hash[$kk[0]]) ) continue;
			$ret.="<input type='hidden' name='$kk[0]' value='$kk[1]' />";
		}
		return $ret;
	}

	static public function disablePOST( )
	{
		if( sizeof($_POST)>0 ) ErrorHelper::get500( );
	}

	static public function enableCaching( $restricted_requests )
	{
		foreach( $restricted_requests as $key=>$value )
		{
			if( isset($_REQUEST[$key]) )
			{
				$test='$ok=('.$value.');';
				eval( $test );
//				print "$test".($ok?"true":"false")."<br/>";
				
				if( !$ok ) return false;
			}
		}

		return true;
	}
}
