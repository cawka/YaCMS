<?php

class PartnerPhotoColumn extends BaseColumn 
{
	var $myWidth;
	var $myHeight;
	
	function __construct( $name,$descr,$required=NULL,$width,$height,$brief=false,$brmsg="" )
	{
		$this->myWidth=$width;
		$this->myHeight=$height;

		parent::__construct( $name,$descr,true,$required,$brief,$brmsg );
		$this->myGenType="photo";
		$this->myIsVisible=true;
	}
	
	function getInsert( &$request )
	{
		if( isAdmin() ) 
			$partner_id=$request["user_id"];
		else
			$partner_id=$_SESSION['user'];
			
		if( isset($_FILES[$this->myName]) && $_FILES[$this->myName]["tmp_name"]!="" )
		{
			$ret=ImageHelper::processImage( $_FILES[$this->myName],"users",$partner_id,"logo" );
	
			return "'$ret'"; //что то еще делать
		}
		else if( $request[$this->myName."_delete"]=='t' )
		{
			system( "rm -R ".IMAGES_STORAGE."users/$partner_id/" );
			return "''"; ///@bug: delete old logo from disk
		}
		else
			return $this->myName;
	}

	function getValue( &$row )
	{
		return $row[$this->myName."_hidden"]; //hack
	}
	
	static public function getPathName( $type,&$request )
	{
		return  IMAGES_STORAGE_HTTP.
				"users"."/".$request["user_id"]."/".
				"logo-$type.jpeg";
	}
	
	function getInput( &$row,$any="" )
	{
		global $langdata;
		
		$ret="";
		if( $this->isAnyImages($row) )
		{
			$ret.="<img src='".$this->getPathName("140x105",$row)."' /><br/>\n";
		}
		//return "<input type='text' name='$name' value='".$this->getValue( )."' />";
		$ret.="<input class='addann_input' 
				name=\"$this->myName$any\" type=\"file\" id=\"images\" 
				size=\"40\" />";
		if( $row[$this->myName]!="" )
			$ret.="<br/><input type='checkbox' name='$this->myName"."_delete' value='t' /> $langdata[delete]";
		return $ret;

	}
	
	function isAnyImages( &$row )
	{
		return $row[$this->myName]!="";
	}

	function isAnyImagesForm( &$row )
	{
		return $row[$this->myName."_hidden"]!="";
	}

	function getFirstImagePreview( &$row )
	{
		return $row[$this->myName];
	}
	
	function extractValue( &$row,$path="" )
	{
		if( $row['is_logo_allowed']!='t' ) return "";
		return "<img src='".PartnerPhotoColumn::getPathName("140x105",$row)."' />";
	}
	
	function extractBigPhoto( &$row )
	{
		if( $row['is_logo_allowed']!='t' ) return "";
		return "<img src='".PartnerPhotoColumn::getPathName("280x210",$row)."' />";
	}
	
	static public function extractListPhoto( &$row )
	{
		if( $row['is_logo_allowed']!='t' ) return "";
		return "<img src='".PartnerPhotoColumn::getPathName("60x45",$row)."' />";
	}
	
	
	
//	function extractBriefValue( &$row )
//	{
//		if( $row['brief_photo']!="" ) 
//			return "<img src=\"".$row['brief_photo']."_brief.jpeg\" />";
//		else
//			return "<img src=\"images/nophoto.gif\" />";
//	}
}

?>
