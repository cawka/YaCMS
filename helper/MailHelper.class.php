<?php

require_once( "lib/Swift/Swift.php" );
require_once( "lib/Swift/Swift/Connection/SMTP.php" );
require_once( "lib/Swift/Swift/Address.php" );
require_once( "lib/Swift/Swift/RecipientList.php" );

class MailHelper 
{
    private static $default_from_name ="Robot";
    private static $default_from_email="no-reply@domain.com";
    
    private static $SMTP="127.0.0.1";

    public static function sendPure( &$from, &$to, &$message )
    {
		try
		{
			$swift=new Swift( new Swift_Connection_SMTP(MailHelper::$SMTP) );
			$ret=$swift->send( $message, $to, $from ); 

			return $ret;
		}
		catch( Swift_ConnectionException $e )
		{
//			print $e->getMessage();
			return false;
		} 
		catch (Swift_Message_MimeException $e) 
		{
//			$e->getMessage();
			return false;
		}
    }
    
    public static function sendToUserFromRobot( $to_name,$to_email,$subject,$html,$txt ) //should be HTML, one recepient
    {	
		$message=new Swift_Message( $subject );
		 
		$message->attach( new Swift_Message_Part($txt) );
		$message->attach( new Swift_Message_Part($html, "text/html") );

		global $SETTINGS;
		if( isset($SETTINGS['robot_from_email']) )
			$from=new Swift_Address( $SETTINGS['robot_from_email'], $SETTINGS['robot_from_name'] );
		else
			$from=new Swift_Address( MailHelper::$default_from_email, MailHelper::$default_from_name );

		$to  =new Swift_Address( $to_email, $to_name );

		return MailHelper::sendPure( $from, $to, $message );
    }
    
    public static function sendToUserFromUser( $from_name,$from_email,$to_name,$to_email,$subject,$txt ) //should be TXT, one recepient
    {
		$message=new Swift_Message( $subject, $txt );
		 
		$from=new Swift_Address( $from_email, $from_name );
		$to  =new Swift_Address( $to_email, $to_name );
		
		return MailHelper::sendPure( $from, $to, $message );
    }
    

	public static function sendFromRobot( $to_list,$subject,$html,$txt )
	{
		$message=new Swift_Message( $subject );
		 
		$message->attach( new Swift_Message_Part($txt) );
		$message->attach( new Swift_Message_Part($html, "text/html") );
	
		if( isset($SETTINGS['robot_from_email']) )
			$from=new Swift_Address( $SETTINGS['robot_from_email'], $SETTINGS['robot_from_name'] );
		else
			$from=new Swift_Address( MailHelper::$default_from_email, MailHelper::$default_from_name );

		$to_array=explode( ",", $to_list );
		$to=new Swift_RecipientList( );
		foreach( $to_array as $i ) $to->add( $i );
	
		return MailHelper::sendPure( $from, $to, $message );
	}
    
	public static function sendToAdminFromUser( $from_name,$from_email,$to_list,$subject,$txt )
	{
		$message=new Swift_Message( $subject, $txt );
	
		$from=new Swift_Address( $from_email, $from_name );
		$to_array=explode( ",", $to_list );
		$to=new Swift_RecipientList( );
		foreach( $to_array as $i ) $to->add( $i );
	
		return MailHelper::sendPure( $from, $to, $message );
	}    
}

