<?php

class TextAreaHTMLColumn extends TextAreaColumn 
{
	function getInputPostfix( )
	{
		global $langdata, $PREFIX;
		
		return "<script type='text/javascript'>
window.addEvent('domready',function(){
        if (CKEDITOR.instances['$this->myName']) {
            CKEDITOR.remove(CKEDITOR.instances['$this->myName']);
            CKEditors.erase( '$this->myName' );
        }       
        var editor=CKEDITOR.replace( '$this->myName', {height: 400} );
        CKEditors.set( '$this->myName', '$this->myName' );

        CKFinder.setupCKEditor( editor, '$PREFIX"."lib/ckfinder/' );
        CKEDITOR.config.contentsCss='$PREFIX"."css/site.css';
    } );
        </script>";
	}
}

