<?php

class TextStaticTextColumn extends BaseColumn
{
	function extractValue( &$row )
	{
		global $langdata;
		
		return $langdata[$row[$this->myName]];
	}
}
