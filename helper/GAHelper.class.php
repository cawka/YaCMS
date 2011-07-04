<?php

require_once( "lib/php-ga/ga.php" ); 

// Google Analytics helper (server-side tracking)
class GAHelper
{
	private $ga;

	public function __construct( $account, $file )
	{
		$this->ga = new Ga( array('account'=>$account, 'file'=>$file) );

		$this->ga->track( );
	}
}

