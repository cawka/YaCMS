<?php

class ImageHelper
{
	public static function checkMinimalSize( &$pfile )
	{
		$size=getimagesize( $pfile['tmp_name'] );
		if( !$size || $size[0]<800 || $size[1]<600 ) return false;
		return true;
	}
	
	public static function processImage( &$pfile,$date,$id,$name )
	{
		global $MY_MAX_PHOTO_SIZE;
		if( $pfile['size']>$MY_MAX_PHOTO_SIZE ) return NULL;
		if( !is_file($pfile['tmp_name']) ) return NULL;

		$size=getimagesize( $pfile['tmp_name'] );
		if( $size && 
			($size[2]==IMAGETYPE_JPEG || 
			 $size[2]==IMAGETYPE_PNG ||
			 $size[2]==IMAGETYPE_GIF) ) // it was a JPEG or PNG image, so we're OK so far
		{
//			print "Preved1 $ret, $target<br/>\n";
			$original=$pfile['tmp_name'];
			$target=IMAGES_STORAGE."$date/$id/$name.jpeg";
			if( is_file(IMAGES_STORAGE."$date/$id/$name.jpeg") )
			{
				system( "rm ".IMAGES_STORAGE."$date/$id/$name* 2>/dev/null" );
			}
			
			if( !is_dir(IMAGES_STORAGE."$date/$id") ) mkdir( IMAGES_STORAGE."$date/$id",0775,true );

			switch( $size[2] )
			{
				case IMAGETYPE_JPEG:
					$origImage=imagecreatefromjpeg( $original ); break;
				case IMAGETYPE_PNG:
					$origImage=imagecreatefrompng( $original ); break;
				case IMAGETYPE_GIF:
					$origImage=imagecreatefromgif( $original ); break;
			}

			$resultImage=ImageHelper::cropImage( $origImage, $size[0],$size[1],0.75 );

            imagealphablending( $resultImage, TRUE );
    
			$ret=imagejpeg( $resultImage,$target,90 );
			chmod( $target, 0644 );

			imagedestroy( $origImage );
            imagedestroy( $resultImage );
            
            return "ok";
		}
		else
			return NULL;
	}

	public static function cropImage( &$source,$width,$height,$ratio )
	{
		$new_w = $width;
		$new_h = $height;
		$offsetX=0;
		$offsetY=0;
		
        if( $height/(float)$width<$ratio  )
		{
			$new_h=floor( $width * $ratio );
			$offsetY=($new_h-$height)/2;
		}
		else if( $height/(float)$width>$ratio )
		{
			$new_w=floor( $height / $ratio );
			$offsetX=($new_w-$width)/2;
		}
		
//		print "$width/$height, ($new_w/$new_h, $offsetX/$offsetY)\n";
			
		$im2 = ImageCreateTrueColor( $new_w, $new_h );
		$bg = imagecolorallocate ( $im2, 255, 255, 255 );
		imagefill ( $im2, 0, 0, $bg );
		
		imagecopy( $im2, $source, $offsetX, $offsetY, 0,0, $width, $height );
		return $im2;
	}
}
