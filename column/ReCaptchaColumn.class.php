<?php

require_once( "../lib/recaptcha/recaptchalib.php" );

class ReCaptchaColumn extends BaseColumn
{
	public function __construct( $name, $descr )
	{
		global $langdata;
		
		parent::__construct( $name, $descr );
		$this->mySQL=false;
		$this->myRequired=$langdata['captcha_error'];
	}

	public function getInput( &$row )
	{
		global $LANG;

		$ret="<div id='$this->myName'></div>";
		$ret.="<script type='text/javascript'>Recaptcha.create( \"".RECAPTCHA_PUBLIC."\", ".
			"\"$this->myName\", { ".
            "theme: \"clear\", ".
            "lang: \"en\" });</script>";

		return $ret;
	}

	function checkBeforeSave( &$request )
	{
		global $langdata;
		
		$resp = recaptcha_check_answer( RECAPTCHA_PRIVATE,
										IPHelper::getIP(),
										$request["recaptcha_challenge_field"],
										$request["recaptcha_response_field"] );
		if( !$resp->is_valid )
		{
			return false;
		}
		else
			return true;
	}
}

