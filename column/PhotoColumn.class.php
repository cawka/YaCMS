<?php

class PhotoColumn extends BaseColumn 
{
	function __construct( $name,$descr,$required=NULL,$width=NULL,$height=NULL,$brief=false,$brmsg="" )
	{
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
        <input type=\"button\" class=\"button\" onClick=\"BrowserPopup('$this->myName');\" value=\"Browse...\" />\n";
		$ret.="<script type='text/javascript'>syncImgValues.periodical(1000,[],[$('$this->myName"."_pic'),$('$this->myName')]);</script>";

		return $ret;

	}
}
