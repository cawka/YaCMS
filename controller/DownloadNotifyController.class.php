<?php

class DownloadNotifyController extends DownloadController
{
	protected function track( $file, $info )
	{
		new GAHelper( $SETTINGS['ga.account'], $file );

		new SearchKeywordEmailerHelper( );
		return parent::track( $file, $info ); // not necessary, but just in case we need to do something there
	}
}

