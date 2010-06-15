<?php

define( "ENCODING", "utf-8" );

function modifyTitle( $origpath )
{
	$delim=" / ";

	$path=explode( ">>",iconv("utf-8",ENCODING,$origpath) );
	
	return implode( $delim,$path );
}

function getHeader( )
{
	return
"	<div class=\"adv_banner_right\">
	<div class=\"head\">TOP advertisments</div>
	<div class=\"head_left\" ></div>
";
}

function getItem( &$item )
{
	static $oddclass=true;
	
	$ret="";
	$ret.="<div class=\"item ";	if( $oddclass ) $ret.="odd"; else $ret.="even"; $ret.="\">\n";

	$ret.="<a target=\"_blank\" href=\"$item->link\">\n";
	$ret.="  <img src=\"$item->photo-60x45.jpeg\" />\n";
	$ret.="  <div class=\"itemhead\">".modifyTitle($item->title)."</div>\n";
	$ret.= str_replace("\n","<br/>",iconv("utf-8",ENCODING,$item->description))."\n";
	$ret.="</a>\n";
	$ret.="</div>\n";
	$oddclass=!$oddclass;
	
	return $ret;
}

function getFooter( )
{
	return 
"	<div class=\"bottom\">
		<table style=\"border: 0; margin: 0\">
		<tr style=\"border: 0\">
			<td style=\"border: 0\">Publish advertisment</td>
			<td style=\"border: 0\"><a href=\"http://reklama.com.ua/advertisment.html\"><img src=\"http://reklama.com.ua/images/place_adv.png\" /></a></td>
		</tr>
		</table>
	</div>
</div>
";
}

function smarty_function_testTeaser( $param )
{
	global $LANG;
	
	$curl = curl_init();

	# CURL SETTINGS.
	curl_setopt($curl, CURLOPT_URL,"http://teasers.reklama.com.ua/table-rus-top5.xml" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);

	# GRAB THE XML FILE.
	$xmlTwitter = curl_exec($curl);
	curl_close($curl);

	//$xml = new SimpleXMLElement($xmlTwitter);
	$xml = simplexml_load_string($xmlTwitter); 

	$ret=getHeader( );
	foreach( $xml->item as $item ) $ret.=getItem( $item );
	$ret.=getFooter( );
	
	return $ret;
}

?>