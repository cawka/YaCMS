<?php

class ClearCacheController extends BaseController 
{
//	function __construct( $php )
//	{
//		parent::__construct( $mode,$helper );
//	}

	function index( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl,$request,"admin/clear_cache.tpl","" );
	}
	
	function clearCache( &$tmpl, &$request )
	{
		global $theAPC;
		$theAPC->clear_all( );
		
		return $this->index( $tmpl,$request );
	}

	function clearCacheSmarty( &$tmpl, &$request )
	{
		global $theAPC;
		$tmpl->cache->clearAll( );
		
		return $this->index( $tmpl,$request );
	}
}

?>
