<?php

require_once( "lib/searchkeys.class.php" );

class SearchKeywordEmailerHelper extends search_keywords
{
  public function __construct( )
  {
  //   if ($_SERVER['REMOTE_ADDR'] == "84.54.99.17") return;
  //   //if ($_SERVER['REMOTE_ADDR'] == "24.24.203.171") return;
  // 			
  //   parent::search_keywords( );
  //   
  //   $host = parse_url ($this->referer, PHP_URL_HOST);
  // 
  //   $keys=$this->get_keys();
  // 
  //   if( sizeof($keys)==0    ||
  // 	$keys[2]=="Unknown" )
  //     {
  // 	return; // without referral field, unknown search engine
  //     }
  // 
  // 	DBHelper::connect ();
  //   global $DB, $SETTINGS;
  // 
  //   $sql="INSERT INTO keywords (date,ip,engine,keywords,referer,url) values(NOW(),".
  //     $DB->qstr( $_SERVER['REMOTE_ADDR'] ).",".
  //     $DB->qstr( $keys[2] ).",".
  //     $DB->qstr( $keys[1] ).",".
  //     $DB->qstr( $_SERVER['HTTP_REFERER'] ).",".
  //     $DB->qstr( $_SERVER['REQUEST_URI'] ).
  //     ")";
  //   $DB->Execute( $sql );
				
/*     $text= */
/*       "Keywords: \"$keys[1]\" */

/* $keys[2]: $_SERVER[REMOTE_ADDR] (http://www.geoiptool.com/en/?IP=$_SERVER[REMOTE_ADDR]) */
	  
/* Found: http://lasr.cs.ucla.edu$_SERVER[REQUEST_URI]. */

/* Sincerely, */
/* Your Home Page Robot"; */

/*     $html= */
/*       "<b>Keywords: </b> <a href=\"$_SERVER[HTTP_REFERER]\"><strong>$keys[1]</strong></a><br/> */
/* <b>$keys[2]: </b> <a href='http://www.geoiptool.com/en/?IP=$_SERVER[REMOTE_ADDR]'>$_SERVER[REMOTE_ADDR]</a><br/> */
/* <br/> */
/* <b>Found: </b> <a href=\"http://lasr.cs.ucla.edu$_SERVER[REQUEST_URI]\">http://lasr.cs.ucla.edu$_SERVER[REQUEST_URI]</a>.<br/> */
/* OA<br/> */
/* <br/> */
/* Sincerely,<br/> */
/* Your Home Page Robot"; */

  //   MailHelper::sendToUserFromRobot( $SETTINGS['user.name'], $SETTINGS['user.email'],
  // 				     "$keys[2] ($host)",
  // 				     $html,
  // 				     $text );
  }
}

