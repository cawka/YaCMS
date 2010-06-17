<?php

class FileColumn extends BaseColumn 
{
	function PhotoColumn( $name,$descr,$required=NULL )
	{
		parent::BaseColumn( $name,$descr,true,$required );
	}
	
	function getValue( &$row )
	{
		return $row[$this->myName];
	}
	
	function getInput( &$row )
	{
		$ret="<input class='addann_photo' name=\"$this->myName\" type=\"text\" id=\"$this->myName\" size=\"80\" value=\"".$this->getValue($row)."\" />
		<script type='text/javascript'>
			function set_$this->myName(val){ $(\"$this->myName\").set('value',val); }
		</script>
        <input type=\"button\" class=\"button\" onClick=\"BrowserPopup('$this->myName');\" value=\"Browse\">\n";
		return $ret;

	}
}



?>
