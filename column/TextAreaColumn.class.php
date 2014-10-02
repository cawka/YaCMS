<?php

class TextAreaColumn extends TextColumn 
{
	function getInput( &$row )
	{
		$classes=array("addann_textarea");
		if( $this->myToolTip!="" ) array_push( $classes, "tooltip" );
		$classes=array_merge( $classes, $this->myValidate );

		$ret="";
		$ret.= "<textarea id='$this->myName' class=\"".implode(" ", $classes)."\"  name='$this->myName' ";
		if( $this->myToolTip!="" ) $ret.=" title='$this->myToolTip' ";
		$ret.=">";
		$ret.=htmlspecialchars($this->getValue( $row ) )."</textarea><br/>\n".
		$this->getInputPostfix( $row );
		
		return $ret;
	}
	
	function getInputPostfix( &$row )
	{
		global $langdata;
		
		$limit="";
		if( $this->myLimit>0 ) $limit="maxChar: $this->myLimit,";

		return "<script>new UvumiTextarea({ selector: 'textarea#$this->myName', minSize: 100, $limit
				txtLimit:'$langdata[textareaLimit]',txtRemainsPrefix:'$langdata[textareaPrefix] ',txtRemainsPostfix:' $langdata[textareaPostfix]' });</script>";
	}
}

