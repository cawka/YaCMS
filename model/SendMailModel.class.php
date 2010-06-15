<?php

//require_once( BASEDIR . "/class/recaptcha/recaptchalib.php" );
//require_once( BASEDIR . "/class/Mail.class.php" );

class SendMailModel extends BaseModel 
{
//	private $myCaptchaPrivateKey="6LckUAEAAAAAADSFrCjjsOUlK-AQcnImNYTYqeAB";
//	private $myCapthcaPublicKey="6LckUAEAAAAAAKWvrknmWKbs2xbiK10K3YAnJnA4";
	
	public $myRes=true;

	public function user2admin( &$request )
	{
		global $SETTINGS;
		//$resp = recaptcha_check_answer( $myCaptchaPrivateKey,
		//                                $_SERVER["REMOTE_ADDR"],
		//                                $request["recaptcha_challenge_field"],
		//                                $request["recaptcha_response_field"] );
		//if( !$resp->is_valid )
		//{
		//	header( "staticpage.php?sp_id=1" );
		//	exit( 0 );
		//}
		//
	
		Mail::sendToAdminFromUser( $request['name'],$request['email'],
								   $SETTINGS['contacts_email'],
								   "[".$request['topic']."] ".$SETTINGS['contacts_subject'],
				"Имя: $request[name]\n\n".
				$request[text].
			    "\n\n\nСообщение послано с IP: ".$_SERVER["REMOTE_ADDR"].
			    "\n\n".$_SERVER['HTTP_USER_AGENT']		   
			);
	}
	
	public function user2user( &$request )
	{
		global $DB, $langdata;
//		$resp = recaptcha_check_answer( $myCaptchaPrivateKey,
//		                                $_SERVER["REMOTE_ADDR"],
//		                                $request["recaptcha_challenge_field"],
//		                                $request["recaptcha_response_field"] );
//	
//		if( !$resp->is_valid )
//		{
//			$tmpl=new ReklamaUA( );
//			$tmpl->prepareOutput( );
//			
//		//	$tmpl->isCached( "sendmessage.tpl" );
//			$tmpl->assign( "pubkey", $myCapthcaPublicKey );
//			$tmpl->assign( "error", $langdata['captcha_error'] );
//			$tmpl->assign( "data", $_REQUEST );
//			$tmpl->display( "sendmessage.tpl" );
//			exit( 0 );
//		}
		
		$id=$request['id'];
		$field=$request['field'];
	
		$tblname=$DB->GetOne( "SELECT type_data FROM v_catalog WHERE lang=1 AND cat_id=(SELECT cat_id FROM data WHERE id='$id')" );
		if( $tblname )
		{
			$email=$DB->GetOne( "SELECT \"$field\" FROM ann_$tblname WHERE id='$id'" );
			
			Mail::sendToUserFromUser( $request['name'],$request['email'],
				 null, $email,
				 "[Reklama.com.ua] ".$request['subject'],
				 "Имя: $request[name]\n\n$request[text]\n------------\n$langdata[email_we_are_the_best]"
				 );
		}		
	}
}
