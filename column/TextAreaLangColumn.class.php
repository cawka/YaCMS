<?php

class TextAreaLangColumn extends TextLangColumn 
{
//	function getInput( &$row )
//	{
//		$ret=textarea_box( array("name"=>$this->myName,"id"=>$row[$this->getUpdateName()],
//								"required"=>$this->myRequired,
//								"cols"=>$this->myCols,"rows"=>$this->myRows) );
//		return $ret.$this->getInputHidden( $row );
//	}	

	protected function getInputReal( $lang, $lang_id, $phrases, $row )
	{
		$classes=array("addann_textarea");
		if( $this->myToolTip!="" ) array_push( $classes, "tooltip" );
		$classes=array_merge( $classes, $this->myValidate );
				
		$ret="<textarea class='".implode(" ", $classes)."' ";
		if( $this->myToolTip!="" ) $ret.=" title='$this->myToolTip' ";
		$ret.=" name='$this->myName"."_$lang' id='$this->myName"."_$lang'>".
		htmlspecialchars($phrases[$lang_id],ENT_QUOTES,"UTF-8").
		"</textarea>".
		$this->getInputPostfix( $lang, $lang_id );
		
		return $ret;
	}
	
	function getInputPostfix( $lang, $lang_id )
	{
		global $langdata;
		$limit="";
		if( $this->myLimit>0 ) $limit=", maxChar: $this->myLimit";

		return "<script>new UvumiTextarea({ selector: 'textarea#$this->myName"."_$lang', minSize: 100 $limit,".
		"txtLimit:'$langdata[textareaLimit]',txtRemainsPrefix:'$langdata[textareaPrefix] ',".
		"txtRemainsPostfix:' $langdata[textareaPostfix]' });</script>";
	}

}

