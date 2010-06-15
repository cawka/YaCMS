<?php

class SiteMapHelper extends BaseHelper
{
	public function getCatalogLink( &$request )
	{
		return (($request['php']=='index.php')?"i":"")."catalog-$request[cat_id].html";
	}
}

?>
