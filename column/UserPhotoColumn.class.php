<?php

class UserPhotoColumn extends BaseColumn 
{
	public $myCount;
	protected $myDir="publ_begin";
	protected $mySubdir="id";
	
	public function __construct( $name,$descr,$brmsg="",$count=1 )
	{
		$this->myCount=$count;
		parent::__construct( $name,$descr,true,NULL,false,$brmsg );
		$this->myGenType="photo";
	}
	
	public function getValue( &$row,$pos=0 )
	{
		return $row[ $this->getName($pos) ];
	}
	
	protected function getName( $i )
	{
		if( $this->myCount>1 ) 
			return "$this->myName$i"; 
		else 
			return $this->myName;
	}
	
	protected function getDir( &$request )
	{
		$ret=date( "Y-m-d", strtotime( $request[$this->myDir] ) );
//		if( !isset($request['cat_tree']) && $ret=="1970-01-01" )
//		{
//			$req=print_r( $request, true );
//			$file=fopen( "/tmp/debug-getdir", "a" );
//			fwrite( $file, $req );
//			fclose( $file );
//		}

		return $ret;
	}
	
	protected function getSubdir( &$request )
	{
		return $request[$this->mySubdir];
	}
	
	public function getPathName( $name,$type,&$request )
	{
		return  IMAGES_STORAGE_HTTP.
				$this->getDir($request)."/".$this->getSubdir($request)."/".
				"$name-$type.jpeg";
	}
	
	public function getPath( $i,$type,&$request )
	{
		return $this->getPathName( $this->getName($i),$type,$request );
	}
	
	private function getInsertArray( &$request )
	{
		$files=array();

		for( $i=0; $i<$this->myCount; $i++ )
		{
			$pos=sizeof( $files );
			
			if( $_FILES[$this->getName($i)]['tmp_name']!="" )
			{
				$ret=ImageHelper::processImage( $_FILES[$this->getName($i)],
										  $this->getDir($request),
										  $this->getSubdir($request),
										  $this->getName($pos) );
			}
			else if( $request[ $this->getName($i) ]=="" )
			{
				system( "rm ".IMAGES_STORAGE.
						$this->getDir($request)."/".
						$this->getSubdir($request)."/".
						$this->getName($i)."*" );
			}
			else
			{
				$ret=$request[ $this->getName($i) ];
				if( sizeof($files)!=$i )
				{
					system( "rm ".IMAGES_STORAGE.
							$this->getDir($request)."/".
							$this->getSubdir($request)."/".
							$this->getName($pos)."*" );
							
					system( "mv ".IMAGES_STORAGE.
							$this->getDir($request)."/".
							$this->getSubdir($request)."/".
							$this->getName($i).".jpeg ".
							
							IMAGES_STORAGE.
							$this->getDir($request)."/".
							$this->getSubdir($request)."/".
							$this->getName($pos).".jpeg" );

					system( "rm ".IMAGES_STORAGE.
							$this->getDir($request)."/".
							$this->getSubdir($request)."/".
							$this->getName($i)."*" );
				}
			}
			if( $ret ) array_push( $files, $ret );
			unset( $ret );
		}
		return $files;
	}
	
	private function escapeAllQuotes( $str )
	{
		$ret=str_replace( "\"", "\\\"", $ret );
		$ret=str_replace( "'", "\\\"", $str );
		return $ret;
	}

	function getInsert( &$request )
	{
		$ret="";
		$files=$this->getInsertArray( $request );
		
		for( $i=0; $i<$this->myCount; $i++ )
		{
			if( $ret!="" ) $ret.=",";
			$ret.="'".$files[$i]."'"; //что то еще делать
		}
		return $ret;
	}
	
	function getUpdate( &$request )
	{
		$ret="";
		$files=$this->getInsertArray( $request );
		for( $i=0; $i<$this->myCount; $i++ )
		{
			if( $ret!="" ) $ret.=",";
			$ret.=$this->getName($i)."="."'".$files[$i]."'";//что то еще делать
		}
		return $ret;
	}
	
	function getUpdateName()
	{
		$ret="";
		for( $i=0; $i<$this->myCount; $i++ )
		{
			if( $ret!="" ) $ret.=",";
			$ret.=$this->getName($i);
		}
		return $ret;
	}
		
	function getInput( &$row )
	{
		global $langdata;
		
		$ret="";
		$temp=array(); $count=0;
		for( $i=$this->myCount-1; $i>=0; $i-- )
		{
			$count=$count+($this->getValue($row,$i)!=""?"1":0);
			$temp[$i]=$count;
		}
		
		for( $i=0; $i<$this->myCount; $i++ )
		{
			$ret.="<div class=\"addann_photocontainer\" id='".$this->getName($i)."' 
					style='display: ".(($temp[$i]!=0 || $i==0)?"inline":"none")."'>\n";
			
			$ret.="<div id=\"_".$this->getName($i)."\">\n";
	
			$real_input="<input class='i' size='40' name='".$this->getName($i)."' type='file' />";
	
			if( $row[$this->getName($i)]!="" )
			{
				$this->myCurrentPhotoVisible=true;
				
				$ret.="<img src=\"".$this->getPath($i,"96x72",$row)."\" />
					  <input type='hidden' name='".$this->getName($i)."' value='".$row[$this->getName($i)]."' />
					  <a href='javascript:;' onclick='$(\"_".$this->getName($i)."\").set(\"html\",\"".$this->escapeAllQuotes($real_input)."\")'>$langdata[delete_photo]</a>
				<br/>\n";
			}
			else
			{
				$this->myCurrentPhotoVisible=null;
				$ret.=$real_input;
			}
			$ret.="</div>\n";
			
			
			if( $i<$this->myCount-1 && $temp[$i+1]==0 )
			{
				$ret.=" <a id='href".$this->getName($i)."' href='javascript:;'
						onclick='$(\"".$this->getName($i+1)."\").style.display=\"inline\";
								 $(\"href".$this->getName($i)."\").style.display=\"none\";'
						>&nbsp;".$langdata['add_photo']."</a>";
			}
			$ret.="</div>\n";
		}
		$ret.="<div style='color: green'>$langdata[userphoto_text]</div>";
		return $ret;
	}
	
	function isAnyImages( &$row )
	{
		$ret=false;
		for( $i=0; $i<$this->myCount; $i++ ) $ret=$ret||$row[$this->getName($i)]!="";
		return $ret;
	}
	
	function isAnyImagesForm( &$row )
	{
		$ret=false;
		for( $i=0; $i<$this->myCount; $i++ ) $ret=$ret||$row[$this->getName($i)."_hidden"]!="";
		return $ret;
	}
	
	function getFirstImagePreview( &$row )
	{
		for( $i=0; $i<$this->myCount; $i++ ) 
		{
			if( $row[$this->getName($i)]!="" )
			{
				return $this->getName($i);
//				return $row[$this->getName($i)];
			}
		}
		return "";
	}

	function extractValue( &$row,$path="" )
	{
		$ret="";
		$first=true;
		
		$images1=array();
		$images2=array();
		for( $i=0; $i<$this->myCount; $i++ ) 
		{
			if( $row[$this->getName($i)]=="" ) continue;
			
			array_push( $images1, "'".$this->getPath($i,"800x600",$row)."'" );
			array_push( $images2, "'".$this->getPath($i,"500x375",$row)."'" );
			
			if( $first )
			{
				$ret.="<a id='".$this->myName."_link' href='".$this->getPath($i,"800x600",$row)."' class='smoothbox'>".
					  "<img id='".$this->myName."_big' src=\"".$this->getPath($i,"500x375",$row)."\" ".
					  "class=\"uphoto_big\"/></a><br/>\n";
			}
			$first=false;
			if( $this->myCount>1 )
			{
				$ret.=  "<a href='javascript:;' ".
						"onclick=\"\$('".$this->myName."_big').src='".$this->getPath($i,"500x375",$row)."'; \$('".$this->myName."_link').href='".$this->getPath($i,"800x600",$row)."'\">".
					 	"<img src=\"".$this->getPath($i,"96x72",$row)."\" ".
					 	"class=\"uphoto_th\" /></a>";
			}
		}
		
		if( !$first )
		{
			$ret.="<script type='text/javascript'>var $this->myName=[".implode(",",$images2)."]; preload($this->myName); preload([".implode(",",$images1)."])</script>";
		}

		return $ret;
	}
	
	function extractAdminValue( &$row,$path="" )
	{
		$ret="";
		$first=true;
		for( $i=0; $i<$this->myCount; $i++ ) 
		{
			if( $row[$this->getName($i)]=="" ) continue;
			$ret.="<img id='".$this->myName."_big' src=\"".$this->getPath($i,"500x375",$row)."\" class=\"fullannphoto\"/><br/>\n";
//			$ret.="<img id='".$this->myName."_big' src=\"$path".$row[$this->getName($i)]."_big.jpeg\" class=\"fullannphoto\"/><br/>\n";
		}
		return $ret;
	}
	
	function extractBriefValue( &$row )
	{
		return "<img width='60px' height='45px' src=\"".UserPhotoColumn::getPhoto( $this->getDir($row),$this->getSubdir($row),
			$row['brief_photo'],"60x45",$row['cat_tree'] )."\" />";
		
//		if( $row['brief_photo']!="" ) 
//			return "<img src=\"".$this->getPathName( $row['brief_photo'],"60x45",$row )."\" />";
//		else
//			return "<img src=\"".IMAGES_STORAGE_HTTP."_images/nophoto.jpeg"."\" />";
		
//		if( $row['brief_photo']!="" ) 
//			return "<img src=\"".$row['brief_photo']."_brief.jpeg\" />";
//		else
//			return "<img src=\"images/nophoto.gif\" />";
	}
	
	function extractXMLValue( $row )
	{
		$ret="\n";
		for( $i=0; $i<$this->myCount; $i++ ) 
		{
			if( $row[$this->getName($i)]=="" ) continue;
			$ret.="\t<photo><![CDATA[".$this->getPath($i,"500x375",$row)."]]></photo>\n";
		}
		return $ret;
	}	
	
	static public function getPhoto( $date,$id,$name,$type,$maincat="" )
	{
		if( $name!="" && $type!="" )
			return IMAGES_STORAGE_HTTP."$date/$id/$name-$type.jpeg";
		else if( $name!="" )
			return IMAGES_STORAGE_HTTP."$date/$id/$name";
		else
		{
			$cats=explode(".",$maincat);
			while( sizeof($cats)>0 )
			{ //try to find appropriate nophoto image
				if( is_file(IMAGES_STORAGE."_images/nophoto".( implode("_",$cats).".png" )) )
				{
					return IMAGES_STORAGE_HTTP."_images/nophoto".( implode("_",$cats).".png" );
				}
				else 
					array_pop( $cats );
			}
			return IMAGES_STORAGE_HTTP."_images/nophoto$maincat.png";
		}
	}
}
