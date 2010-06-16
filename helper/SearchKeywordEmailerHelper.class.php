<?php

require_once( BASEDIR . "/class/searchkeys.class.php" );
require_once( BASEDIR . "/class/Mail.class.php" );

class SearchKeywordEmailerHelper extends search_keywords
{
		public function __construct( )
		{
				parent::search_keywords( );

				$keys=$this->get_keys();
				if( sizeof($keys)==0    ||
					$keys[2]=="Unknown" )
				{
					return; // without referral field, unknown search engine
				}

				global $DB, $SETTINGS;

				$sql="INSERT INTO keywords (date,ip,engine,keywords,referer) values(NOW(),".
						$DB->qstr( $_SERVER['REMOTE_ADDR'] ).",".
						$DB->qstr( $keys[2] ).",".
						$DB->qstr( $keys[1] ).",".
						$DB->qstr( $_SERVER['HTTP_REFERER'] ).")";
				$DB->Execute( $sql );
				
$text=
"Hi ".$SETTINGS["user.name"].",

Someone from IP address $_SERVER[REMOTE_ADDR] (http://www.geoiptool.com/en/?IP=$_SERVER[REMOTE_ADDR]) just searched for you on $keys[2], and found your home page.

He or she used keywords: \"$keys[1]\"

I don't really know what was rank of the search result, but you can check analytics report.

Sincerely,
Your Home Page Robot";

$html=
"Hi ".$SETTINGS["user.first_name"].",<br/>
<br/>
Someone from IP address <a href='http://www.geoiptool.com/en/?IP=$_SERVER[REMOTE_ADDR]'>$_SERVER[REMOTE_ADDR]</a> just searched you on $keys[2], and found your home page.<br/>
<br/>
He or she used keywords: <a href=\"$_SERVER[HTTP_REFERER]\"><strong>$keys[1]</strong></a><br/>
<br/>
Sincerely,<br/>
Your Home Page Robot";

				Mail::sendToUserFromRobot( $SETTINGS['user.name'], $SETTINGS['user.email'],
						"Someone just searched for you on $keys[2]",
						$html,
						$text );
		}
}

