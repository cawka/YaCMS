<?php

class PhoneHelper
{
	static public $myPhone;

	static public function formatPhone( $row )
	{
		return PhoneHelper::$myPhone->extractPreviewValue( $row );
	}
}

PhoneHelper::$myPhone=new PhoneColumn( "phone", "" );