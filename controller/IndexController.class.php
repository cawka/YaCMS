<?php

class IndexController extends BaseController
{
		public function index( )
		{
				global $SETTINGS;
				header( "Location: $SETTINGS[index]" );
		}	
}

?>
