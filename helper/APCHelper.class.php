<?php
/**
 * Interface to APC engine
*/

class APCHelper
{
	var $myIsEnabled=false;
	var $myTTL;
	
	var $myMemCache;

	var $PREFIX="R_";
	
	function __construct( $enable,$ttl=0 )
	{
		global $CACHE_SERVERS;

		if( sizeof($CACHE_SERVERS)==0 )
		{
			$this->myIsEnabled=false;
			return;
		}

		$this->myIsEnabled=$enable;//isAdmin()?false:$enable;
		$this->myTTL=$ttl;
		
		if( $this->myIsEnabled )
		{
			$this->myMemCache=&new Memcache( );
			foreach( $CACHE_SERVERS as $server )
			{
				$this->myMemCache->addServer( $server['host'], $server['port'], true, $server['weight'] );
			}
		}


//		print_r( $this->myMemCache->getStatus( "item" ) );
	}
	
	function fetch( $key )
	{
		if( !$this->myIsEnabled ) return false;
		return $this->myMemCache->get( $this->PREFIX.$key );
//		return apc_fetch( $key );
	}
	
	function cache( $key, &$value, $ttl=-1 )
	{
		if( !$this->myIsEnabled ) return;
//		$ret=apc_store( $key, $value, $this->myTTL );
		$this->myMemCache->set( $this->PREFIX.$key, $value, false, $ttl>=0?$ttl:$this->myTTL );//$ttl>=0?$ttl:$this->myTTL );
	}
	
	function clear( $key )
	{
		if( !$this->myIsEnabled ) return;
//		apc_delete( $this->PREFIX.$key );
		$this->myMemCache->delete( $this->PREFIX.$key );
	}

	function clear_regexp( $reg_exp ) // regexp reprefix
	{
		if( !$this->myIsEnabled ) return;
		$cache=$this->myMemCache->getstats( "items" );

		foreach( $cache['items'] as $i=>$limit )
		{
			$items=$this->myMemCache->getstats( "cachedump $key $limit[number]" );
			foreach( $items as $key=>$value )
			{
				if( preg_match($reg_exp,$this->PREFIX.$key) ) $this->myMemCache->delete( $this->PREFIX.$key );
			}
		}
	}
	
	function clear_all( )
	{
		if( !$this->myIsEnabled ) return;
		$this->myMemCache->flush( );
	}
}

function APC_constructName( $params )
{
	return implode( "|", $params );
}

function APC_ClearCaches( $params )
{
	global $theAPC;
	$name=APC_constructName( $params );

	$theAPC->clear( $name );
}

function APC_GetRows( $params, &$db, $sql, $ttl=-1 )
{
	global $theAPC;
	$name=APC_constructName( $params );

//	$theAPC->clear( $name );
	$ret=$theAPC->fetch( $name );
	if( !$ret ) 
	{
		$res=$db->Execute( $sql );
		$ret=$res->GetRows();
		
		if( sizeof($ret)==0 ) $ret="no data"; //bug: if no data present - APC thinks that no cache is available
		$theAPC->cache( $name, $ret,$ttl );
	}
	
	return is_array($ret)?$ret:array();
}

function APC_GetAssoc( $params, &$db, $sql, $ttl=-1 )
{
	global $theAPC;
	
	$name=APC_constructName( $params );

	$ret=$theAPC->fetch( $name );
	if( !$ret ) 
	{
		$ret=$db->GetAssoc( $sql );
		
		if( sizeof($ret)==0 ) $ret="no data";
		$theAPC->cache( $name, $ret,$ttl );
	}
	
	return is_array($ret)?$ret:array();
}


function APC_GetRow( $params, &$db, $sql, $ttl=-1 )
{
	global $theAPC;
	
	$name=APC_constructName( $params );

	$ret=$theAPC->fetch( $name );
	if( !$ret || $ret="no data" ) 
	{
		$ret=$db->GetRow( $sql );

		if( sizeof($ret)==0 ) $ret="no data";
		$theAPC->cache( $name, $ret,$ttl );
	}

	return is_array($ret)?$ret:array();
}
