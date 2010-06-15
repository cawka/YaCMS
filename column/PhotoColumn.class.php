<?php

class PhotoColumn extends BaseColumn 
{
	var $myWidth;
	var $myHeight;
	
	function __construct( $name,$descr,$required=NULL,$width,$height,$brief=false,$brmsg="" )
	{
		$this->myWidth=$width;
		$this->myHeight=$height;
		$descr.="<br/><small>Картинка формата JPG, GIF или PNG размера ".$this->myWidth."x".$this->myHeight."</small>";
		parent::__construct( $name,$descr,true,$required,$brief,$brmsg );
	}
	
	function getValue( &$row )
	{
		return $row[$this->myName];
	}
	
	function getInput( &$row )
	{
		$ret="<img src=\"".$this->getValue($row)."\" border=\"0\" id=\"$this->myName"."_pic\" style='height:100px; border:0' /><br />
        <input class='i' name=\"$this->myName\" type=\"text\" id=\"$this->myName\" size=\"40\" value=\"".$this->getValue($row)."\" />
		<script type='text/javascript'>
			function set_$this->myName(val){ updateImage('$this->myName',val); };
		</script>
        <input type=\"button\" class=\"button\" onClick=\"BrowserPopup('$this->myName');\" value=\"Каталог загрузок\">\n";
		return $ret;

	}
}
