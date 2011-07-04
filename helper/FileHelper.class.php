<?php

class FileHelper
{
	private static $RECOGNIZED_EXTENSIONS=array(
	                   "ai",   "aiff",  "bz2",   "c",     "chm",
	                   "conf", "cpp",   "css",   "csv",   "deb",   "divx",  "doc",
	                   "gif",   "gz",    "hlp",   "htm",   "html",  "iso",
	                   "jpeg", "jpg",   "js",    "mov",   "mp3",   "mpg",   "odc",
	                   "odf",  "odg",   "odi",   "odp",   "ods",   "odt",   "ogg",
	                   "pdf",  "pgp",   "php",   "pl",    "png",   "ppt",   "pptx", "ps",
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


    public static function returnMIMEType( $extension )
    {
        switch( strtolower($extension) )
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

			case "js":
                return "application/x-javascript";
            case "json":
                return "application/json";
            case "jpg":
            case "jpeg":
            case "jpe":
                return "image/jpg";
            case "png":
            case "gif":
            case "bmp":
            case "tiff":
                return "image/".strtolower($matches[1]);
            case "css":
                return "text/css";
            case "xml":
                return "application/xml";
            case "doc":
            case "docx":
                return "application/msword";
            case "xls":
            case "xlt":
            case "xlm":
            case "xld":
            case "xla":
            case "xlc":
            case "xlw":
            case "xll":
                return "application/vnd.ms-excel";
            case "ppt":
            case "pps":
                return "application/vnd.ms-powerpoint";
            case "rtf":
                return "application/rtf";
            case "pdf":
                return "application/pdf";
            case "html":
            case "htm":
            case "php":
                return "text/html";
            case "txt":
                return "text/plain";
            case "mpeg":
            case "mpg":
			case "mpe":
                return "video/mpeg";
            case "mp3":
                return "audio/mpeg3";
            case "wav":
                return "audio/wav";
            case "aiff":
            case "aif":
                return "audio/aiff";
            case "avi":
                return "video/msvideo";
            case "wmv":
                return "video/x-ms-wmv";
            case "mov":
                return "video/quicktime";
            case "zip":
                return "application/zip";
            case "tar":
                return "application/x-tar";
            case "swf":
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }

            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
}

