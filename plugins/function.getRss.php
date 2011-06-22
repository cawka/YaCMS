<?php 

require_once( "lib/rssFeed.class.php" );

function smarty_function_getRss( $params, &$smarty )
{
	$type="stories";
    if( isset($params['type']) ) $type=$params['type'];
	$url=$params['url'];

	global $rss;
	$rss=new rssFeed( $url );

	$new_smarty = new MySmarty();

	// If there was an error getting the data
	//uncoment this later
	if( $rss->error )
	{
		return "<h1>Error:</h1>\n<p><strong>$rss->error</strong></p>";
	}
	else
	{
		// Otherwise, we have the data, so we call the parse method
		$rss->parse( );

		$new_smarty->assign( "rss", $rss );
		$new_smarty->assign( "limit", $params['limit'] );

		return $new_smarty->fetch( "blocks/rss-$type.tpl" );
	}
}

