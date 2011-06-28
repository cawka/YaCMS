<?php

class FileHelper
{
	private static $RECOGNIZED_EXTENSIONS=array(
	                   "ai",   "aiff",  "bz2",   "c",     "chm",
	                   "conf", "cpp",   "css",   "csv",   "deb",   "divx",  "doc",
	                   "gif",   "gz",    "hlp",   "htm",   "html",  "iso",
	                   "jpeg", "jpg",   "js",    "mov",   "mp3",   "mpg",   "odc",
	                   "odf",  "odg",   "odi",   "odp",   "ods",   "odt",   "ogg",
	                   "pdf",  "pgp",   "php",   "pl",    "png",   "ppt",   "ps",
	                   "py",   "ram",   "rar",   "rb",    "rm",    "rpm",
	                   "rtf",  "sql",   "swf",   "sxc",   "sxd",   "sxi",   "sxw",
	                   "tar",  "tex",   "tgz",   "txt",   "vcf",   "wav",   "wma",
	                   "wmv",  "xls",   "xml",   "xpi",   "xvid",  "zip",
	                   );

	public static function extension( $file, $default="file" )
	{
	  	//global $RECOGNIZED_EXTENSIONS;
	
	    $fileExp = explode('.', $file); // make array off the periods
	    $filetype = $fileExp[count($fileExp) -1]; // file extension will be last index in array, -1 for 0-based indexes
	    if( isIn($filetype,self::$RECOGNIZED_EXTENSIONS) )
	        return $filetype;
	    else
			return $default;
	}
}

