<?php

class TransactionsHelper extends BaseTableThickBoxHelper 
{
	public function getAdv( $params )
	{
		global $langdata, $SERVICES, $DURATIONS;
		$list=preg_split( "/&/", $params );
		$adv=""; $services="";
		foreach( $list as $value )
		{
			$keyvalue=preg_split( "/=/", $value );
			if( $keyvalue[0]=="adv" ) $adv=$keyvalue[1];
			elseif( $keyvalue[0]=="services" ) $services=preg_split("/,/",$keyvalue[1]);
			elseif( $keyvalue[0]=="duration" ) $duration=$keyvalue[1];
		}
		$ret="Объявление <a href='/show-$adv.html' target='_blank'>№".$adv."</a><br/>&nbsp;&nbsp;&nbsp;&nbsp;<strong>";
		foreach( $services as $service )
		{
			$dur=preg_split("/,/",$DURATIONS[$service]);
			$ret.=$SERVICES[$service]." (".$dur[$duration]." дней) &nbsp; ";
		}
		$ret.="</strong>";
		return $ret;
	}
}
