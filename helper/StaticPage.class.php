<?php

class StaticPage
{
	var $myInfo;
	var $myTpl="static.tpl";
	var $myUseSmartyFetch=false;
	
	function StaticPage( &$info )
	{
		$this->myInfo=$info;
	}
	
	function show( &$tmpl )
	{
		global $DB;
//		$DB->debug=true;
		
		if( !$tmpl->isCached($this->myTpl,$this->myInfo['type_info']) )
		{
			$this->myData=$DB->GetRow( "SELECT * FROM static WHERE st_name='".$this->myInfo['type_info']."'" ); 
			$tmpl->assign( "this", $this );
		}
		if( !$this->myUseSmartyFetch )
			$tmpl->display( $this->myTpl,$this->myInfo['type_info'] );
		else
			return $tmpl->fetch( $this->myTpl,$this->myInfo['type_info'] );
	}
	
	function showDefault( &$tmpl, &$request )
	{
		return $this->show( $tmpl );
	}
}

?>
