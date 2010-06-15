<?php

class StaticTextColumn extends BaseColumn 
{
	function extractValue( &$row )
	{
		global $langdata;
		
		return $langdata[$row[$this->myName]];
	}
}
