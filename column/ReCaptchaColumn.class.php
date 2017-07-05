<?php

require_once( BASEDIR . "/CMS/lib/recaptcha/recaptchalib.php" );

class ReCaptchaColumn extends BaseColumn
{
	public function __construct( $name, $descr )
	{
		global $langdata;
		
		parent::__construct( $name, $descr );
		$this->mySQL=false;
		$this->myRequired="Enter the security code";
	}

	public function getInput( &$row )
	{
		global $LANG;

		$ret = "<div id='$this->myName'></div>";
		$ret .= recaptcha_get_html (RECAPTCHA_PUBLIC);
#		$ret.="<script type='text/javascript'>Recaptcha.create( \"".RECAPTCHA_PUBLIC."\", ".
#			"\"$this->myName\", { ".
#            "theme: \"clear\", ".
#            "lang: \"en\" });</script>";

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
			$this->myError="Incorrect security code";
			return false;
		}
		else
		{
			return true;
		}
	}
}

