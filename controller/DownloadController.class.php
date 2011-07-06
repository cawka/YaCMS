<?php

/*
 * This module is a potential security risk. 
 * myAllowedDirs and myAllowedExtensions should be configured properly!!!
 */
class DownloadController
{
	public $myModel;
	public $myHelper;
	public $myAction;

	public $myAllowedDirs=array( "data", "papers", "talks");
	public $myAllowedExtensions=array( 
		"pdf", "ppt", "pptx", "doc", "docx", "ps", "gz", "tgz", "txt"
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
	
	protected function track( $file, $fileinfo )
	{
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

		$this->track( $request['file'], $info );

//		header( 'Content-Description: File Transfer');
		header( 'Content-Type: '.FileHelper::returnMIMEType($info['extension']) );
		if( $info['extension']!="txt" ) 
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

