<?php

class NavHelper
{
}

function isIn( $value, $list )
{
	if( !is_array($list) )
		return $value==$list;
	else 
	{
		foreach( $list as $test ) if( $value==$test ) return true;
		return false;
	}
}

function getRequestFrom( &$request, &$skip,$delim="&amp;" )
{
	$get="";
	foreach( $request as $key=>$value )
	{
		if( isIn($key,$skip) ) continue;
		if( is_array($value) )
		{
			foreach($value as $subkey=>$subvalue )
			{
				$get.="$key%5B$subkey%5D=$subvalue$delim";		
			}
		}
		else
			$get.="$key=$value$delim";
	}
	return $get;
}

function getRequest( $arr )
{
	global $_GET;
	$request=array_merge( $_POST,$_GET );
	
	$skip=$arr["skip"];
	$skip1=$arr["skip1"];
	$skip2=$arr["skip2"];
	$skip3=$arr["skip3"];
	$delim=$arr["delim"]; if( $delim=="" ) $delim="&amp;";
	if( is_array($skip) )
		$skip=array_merge( $skip, array($skip1,$skip2,$skip3) );
	else 
		$skip=array($skip,$skip1,$skip2,$skip3);

	return getRequestFrom( $request, $skip, $delim );
}

function getAllRequest( $arr )
{
	global $_POST,$_GET;
	$request=array_merge( $_POST, $_GET );
	
	$skip=$arr["skip"];
	$skip1=$arr["skip1"];
	$skip2=$arr["skip2"];
	$skip3=$arr["skip3"];
	if( is_array($skip) )
		$skip=array_merge( $skip, array($skip1,$skip2,$skip3) );
	else 
		$skip=array($skip,$skip1,$skip2,$skip3);
			
	return getRequestFrom( $request, $skip );
}

function prepage()
{
        if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
        {
                $page = $_REQUEST['page'];
        } else {
                $_REQUEST['page'] = 1;
                $page = 1;
        }
        return $page;
}

function pagenav($anzahl_seiten, $tpl_on, $tpl_off) 
{
        global $lang, $langdata;
        $aktuelle_seite = prepage();

        $seiten = array (
        $aktuelle_seite-3,
        $aktuelle_seite-2,
        $aktuelle_seite-1,
        $aktuelle_seite,
        $aktuelle_seite+1,
        $aktuelle_seite+2,
        $aktuelle_seite+3
        );

        $seiten = array_unique($seiten);
        if($anzahl_seiten > 1)
        {
                $nav .= str_replace("{t}", $langdata['navi_first'], str_replace("{s}", 1, $tpl_off));
        }

        if($aktuelle_seite > 1)
        {
                $nav .= str_replace("{t}", $langdata['navi_prev'], str_replace("{s}", ($aktuelle_seite-1), $tpl_off));
        }

        while(list($key,$val) = each($seiten)) 
        {
                if($val >= 1 && $val <= $anzahl_seiten) {
                        if($aktuelle_seite == $val) {
                                $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_on);
                        } else {
                                $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_off);
                        }
                }
        }

        if($aktuelle_seite < $anzahl_seiten) {
                $nav .= str_replace("{t}", $langdata['navi_next'], str_replace("{s}", ($aktuelle_seite+1), $tpl_off));
        }

        if($anzahl_seiten > 1){
                $nav .= str_replace("{t}", $langdata['navi_last'], str_replace("{s}", $anzahl_seiten, $tpl_off));
        }
        return $nav;
}


// ARTIKEL-NAV


function artpage( $anzahl_seiten,$tpl_on, $tpl_off ) 
{
        global $lang;
        $aktuelle_seite = $_REQUEST['artpage'];
        $seiten = array($aktuelle_seite-3,
        $aktuelle_seite-2,
        $aktuelle_seite-1,
        $aktuelle_seite,
        $aktuelle_seite+1,
        $aktuelle_seite+2,
        $aktuelle_seite+3);
        $seiten = array_unique($seiten);
        if($anzahl_seiten > 1){
                $nav .= str_replace("{t}", $lang['pagestart'], str_replace("{s}", 1, $tpl_off));
        }

        if($aktuelle_seite > 1) {
                $nav .= str_replace("{t}", $lang['pageprev'], str_replace("{s}", ($aktuelle_seite-1), $tpl_off));
        }

        while(list($key,$val) = each($seiten)) {
                if($val >= 1 && $val <= $anzahl_seiten) {
                        if($aktuelle_seite == $val) {
                                $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_on);
                        } else {
                                $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_off);
                        }
                }
        }

        if($aktuelle_seite < $anzahl_seiten) {
                $nav .= str_replace("{t}", $lang['pagenext'], str_replace("{s}", ($aktuelle_seite+1), $tpl_off));
        }

        if($anzahl_seiten > 1){
                $nav .= str_replace("{t}", $lang['pageend'], str_replace("{s}", $anzahl_seiten, $tpl_off));
        }
        return $nav;
}
?>
