<?php

class TextAreaHTMLLangColumn extends TextAreaLangColumn 
{
	function getInputPostfix( $lang, $lang_id )
	{
		global $langdata, $PREFIX;
		$name="$this->myName"."_$lang";

		return "<script type='text/javascript'>
window.addEvent('domready',function(){
        if (CKEDITOR.instances['$name']) {
            CKEDITOR.remove(CKEDITOR.instances['$name']);
            CKEditors.erase( '$name' );
        }       
        var editor=CKEDITOR.replace( '$name', {height: 400} );
        CKEditors.set( '$name', '$name' );

        CKFinder.setupCKEditor( editor, '$PREFIX"."lib/ckfinder/' );
//        CKEDITOR.config.contentsCss='$PREFIX"."css/site.css';
    } );
        </script>";
	}
}
