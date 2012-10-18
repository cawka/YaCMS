<?php

class TextAreaHTMLColumn extends TextAreaColumn 
{
	function getInputPostfix( $row )
	{
		global $langdata, $PREFIX;

		if (!isset($row['format']))
		{
		
		return "<script type='text/javascript'>
window.addEvent('domready',function(){
        if (CKEDITOR.instances['$this->myName']) {
            CKEDITOR.remove(CKEDITOR.instances['$this->myName']);
            CKEditors.erase( '$this->myName' );
        }       
        var editor=CKEDITOR.replace( '$this->myName', {height: 400} );
        CKEditors.set( '$this->myName', '$this->myName' );

        CKFinder.setupCKEditor( editor, { basePath : '$PREFIX"."lib/ckfinder/', rememberLastFolder : true } );
        CKEDITOR.config.contentsCss='$PREFIX"."css/site-ckeditor.css';
    } );
		</script>";
		}
		else
		{
			return parent::getInputPostfix ($row);
			// do nothing
		}
	}
}

