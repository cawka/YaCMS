<?php

class BaseTableHelper extends BaseHelper
{
	public function closeWindow( )
	{
		print "<script>window.opener.parent.location.reload(); window.close();</script>";
	}	
	
	public function form_action( &$model, $action, $validate,$params=array(),&$options )
	{
		global $PREFIX;

		$ret="<form action='$PREFIX$model->myPhp/$action' class='validate' ";
		foreach( $options as $key=>$value )
		{
			$ret.=" $key='$value'";
		}
		$ret.=">";

		foreach( $params as $key=>$value )
		{
			$ret.="<input type='hidden' name='$key' value='$value' />";
		}
		return $ret;
	}
	
	public function link( &$model, $action, $name, &$params )
	{
	}

	public function link_popup_post( &$model, $action, $name, &$params )
	{
	}
	
	public function link_popup( &$model, $action, $name, $title, &$params, $method="get" )
	{
		global $PREFIX;

		$ret="";
		$url="$PREFIX$model->myPhp/$action";
		$query=http_build_query( $params,'', '&amp;' );
		if( $query!="" ) $url.="?$query";
		
		$ret.="<a href=\"javascript:void(0);\" onclick=\"window.open('$url',".
				"'_blank','scrollbars=1,toolbar=0,resizable=1')\" >$name</a>";
		return $ret;
	}

	public function link_popup_confirm( &$model, $action, $name, &$params, $confirm_text, $method="get" )
	{
		global $PREFIX;
		$ret="";
		$url="$PREFIX$model->myPhp/$action";
		$query=http_build_query( $params,'', '&amp;' );
		if( $query!="" ) $url.="?$query";
		
		$ret.="<a href=\"javascript:void(0);\" onclick=\"if( confirm('$confirm_text') ) window.open('$url',".
				"'_blank','scrollbars=1,toolbar=0,resizable=1')\" >$name</a>";
		return $ret;
	}
	
	public function img_button( $button, $name )
	{
		global $PREFIX;

		return "<img style='margin:0;padding:0;display:inline' height='12px' src='$PREFIX"."images/admin/$button.gif' alt='$name' title='$name' class='tooltip' />";
	}
}

?>
