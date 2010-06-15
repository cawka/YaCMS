<?php

class PhotoLangTextAreaColumn extends PhotoLangColumn
{	
	private function getInputBox( $name,$id,$required )
	{
		global $DB,$LANGS,$LANGS_rev;
		
		$ret="";
		$phrases=getPhrases( $id );
		
		foreach( $LANGS as $lang_id => $lang )
		{
			$ret.=country_flag( $lang )."<br/>";
			$ret.="<img src=\"".htmlspecialchars($phrases[$lang_id],ENT_QUOTES,"cp1251")."\" border=\"0\" id=\"$name"."_$lang"."_pic\" style='height:100px; border:0' ><br />";
			
			$ret.="<textarea class='addann_textarea' name='$name"."_$lang' id='$name"."_$lang'>";
			if( $id>0 ) $ret.="".htmlspecialchars($phrases[$lang_id],ENT_QUOTES,"cp1251")."";
//			$ret.="	tmt:image=\"true\" $class $style >
			$ret.="</textarea>
			<script type='text/javascript'>
				function set_$name"."_$lang(val){ updateImage('$name"."_$lang',val); };
			</script>
	        <input type=\"button\" class=\"button\" onClick=\"BrowserPopup('$name"."_$lang');\" value=\"Каталог загрузок\"><br/>\n";
		}
		return $ret;
	}
	

	function getInput( &$row )
	{
		$ret=$this->getInputBox( $this->myName, $row[$this->getUpdateName()], $this->myRequired );
		return $ret.$this->getInputHidden( $row );
	}
}
