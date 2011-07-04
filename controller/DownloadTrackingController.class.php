<?php

/*
 * This module is a potential security risk. 
 * myAllowedDirs and myAllowedExtensions should be configured properly!!!
 */
class DownloadTrackingController
{
	public $myModel;
	public $myHelper;
	public $myAction;

	public $myAllowedDirs=array( "data", "papers", "talks");
	public $myAllowedExtensions=array( 
		"pdf", "ppt", "pptx", "doc", "docx", "ps", "gz", "tgz"
	);

	public function __construct( &$model,&$helper )
	{
//		$this->myModel=&$model;
//		$this->myHelper=&$helper;
//		$this->myModel->myHelper=&$helper;
//
//		$this->myHelper->myModel=&$this->myModel;
//		$this->myHelper->myController=&$this;
	}
	
	public function index( &$tmpl,&$request ) 
	{
		$file=BASEDIR . "/www/" . $request['file'];
		if( !isset($request['file']) || !file_exists($file) || !is_file($file) )
		{
			header( 'HTTP/1.0 404 Not Found', true, 404 );
			header('Status: 404 Not Found');
			die;
		}

		$isAllowedDir=false;

		foreach( $this->myAllowedDirs as $dir )
		{
			if( preg_match("|^$dir/.*|", $request['file']) )
			{
				$isAllowedDir=true;
				break;
			}
		}	

		$info=pathinfo($file);

		if( !$isAllowedDir || !isIn($info['extension'], $this->myAllowedExtensions) )
		{
			header('HTTP/1.1 404 Not Found', true, 404 );
			header('Status: 404 Not Found');
			die;
		}

		global $SETTINGS;

		new GAHelper( $SETTINGS['ga.account'], $request['file'] );

		/**
		 * Also track locally
		 * */
		if( $SETTINGS['download.tracking'] )
		{
			$date = date(DATE_RFC822);

			file_put_contents( $SETTINGS['download.tracking'], 
				$request['file']."|"."$date|".$_SERVER['REMOTE_ADDR']."\n", 
				FILE_APPEND | LOCK_EX );
		}


		header( 'Content-Description: File Transfer');
		header( 'Content-Type: '.FileHelper::returnMIMEType($info['extension']) );
		header( 'Content-Disposition: attachment; filename='.basename($file));
		header( 'Content-Transfer-Encoding: binary');
		header( 'Expires: 0');
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header( 'Pragma: public');
		header( 'Content-Length: ' . filesize($file));

	    ob_clean();
	    flush();
		readfile( $file );

		exit( 0 );
	}
}

