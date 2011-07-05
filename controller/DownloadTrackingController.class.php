<?php

class DownloadTrackingController extends DownloadController
{
	protected function track( $file, $info )
	{
		global $SETTINGS;

		new GAHelper( $SETTINGS['ga.account'], $file );

		/**
		 * Also track locally
		 * */
		if( $SETTINGS['download.tracking'] )
		{
			$date = date(DATE_RFC822);

			file_put_contents( $SETTINGS['download.tracking'], 
				$file."|"."$date|".$_SERVER['REMOTE_ADDR']."\n", 
				FILE_APPEND | LOCK_EX );
		}

		return parent::track( $file, $info ); // not necessary, but just in case we need to do something there
	}
}

