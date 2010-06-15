<?php

class BannerHelper
{
	/**
	 * Helper function to display actual banner
	 *
	 * @param int $pos 		Predefined size for the banner
	 * 						0 - 728x90; 1,5 - 250x200; 2 - 250x410; 4 - 650x86; 6 - $SETTINGS['banner_width'] x $SETTINGS['banner_height']
	 * @param int $type  	0 - image banner, 1 - flash banner, 2 - text banner, 3 - any code
	 * @param int $id		Banner ID (from the database table `banners` field `b_id`)
	 * @param String $banner Image filename/Flash filename/Text banner/Actual banner code
	 * @param String $link   Link where banner is pointing to
	 * @param int $height_   Banner height (valid only for $type==7 banner)
	 * @return String DIV block with the banner
	 */
	static public function displayBanner( $pos, $type, $id, $banner, $link, $height_=0 )
	{
		global $SETTINGS;

		switch( $pos )
		{
			case 0:
				$width=728; $height=90;
				$class="banner468x60";
				break;
			case 1: 
			case 5:
				$width=250; $height=200;
				$class="banner200x150";
				break;
			case 2:
				$width=250; $height=410; 
				$class="banner200x320";
				break;
			case 4:
				$width=650; $height=85; 
				$class="banner650x85";
				break;
			case 6:
				$width=$SETTINGS['banner_width'];
				$height=$SETTINGS['banner_height'];
				break;
			case 7:
				$class="verticalBanner";
				$width=240;
				$height=$height_;
		}

		switch( $type )
		{
			case 0:
				return "<div class='$class'><a target='_blank' href='$link'><img style=\"width:$width"."px; height:$height"."px\" src='".$banner."' /></a></div>";
				break;
			case 1:
				$id=rand();
				$link=urlencode($link);
				$ret="<div class='$class' id='$id'></div>";
				$ret.="<script type='text/javascript'>
	new Swiff( '$banner?url=$link',
	{
			id: '$banner',
			width: '$width"."px',
			height: '$height"."px',
			container: $('$id'),

			params: {
				wMode: 'transparent',
				play: 'true',
				loop: 'true',
				scale: 'noborder',
				quality: 'high',
				align: 'middle'
			}
		}
	);</script>";		
				$ret.="";

				return $ret;
					break;
			case 2:
				return "<div class='$class'><a target='_blank' href='$link'>".$banner."</a></div>";
				break;
			case 3:
				return "<div class='$class'>$banner</div>";
				break;
		}		
	}


	static public function getIP( )
	{
		if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) 
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else 
		{
			$ip=$_SERVER['REMOTE_ADDR'];;
		}
		$t=preg_split("/,/",$ip);
		$ip=$t[0];
		return $ip;
	}


	/**
	 * Randomly get one of the banners from the pool identified by $type
	 *
	 * @param int $type		Pool identificator == banner type
	 * @param int $catid	Pool filter: parent catalog_id
	 * @return assoc_array( $params['type'],$params['format'],$params['id'],$params['value'],$params['link'] )
	 * 		   if there is banner and NULL if banner cannot be found
	 */
	static public function getBannerFromPool( $type, $catid )
	{
		global $DB,$LANG;
//		if( isAdmin() ) $DB->debug=true;

		if( isset($catid) ) 
		{
			$row=APC_GetRow( array("catalog",$catid,"cat_tree"), $DB, "SELECT cat_tree FROM catalog WHERE cat_id=$catid" );
			$cat_tree=$row[ 'cat_tree' ];
			$s=" (bc_ltree @> '$cat_tree'::ltree OR bc_cat_id IS NULL) ";
			$catid="'$catid'";
		}
		else
		{
			$s=" bc_cat_id IS NULL ";
			$catid="NULL";
		}

		//$banner=$DB->GetRow( "SELECT * FROM v_banners WHERE lang='$LANG' AND b_pos='$type' and $s 
		//					OFFSET get_catalog_count($catid,'$type') LIMIT 1" ); 

		$banners=APC_GetRows( array("banners",$LANG,$type,$catid), $DB,
			"SELECT * FROM v_banners WHERE lang='$LANG' AND b_pos='$type' and $s", -1 );

		if( sizeof($banners)==0 ) return NULL;
		
		$b=rand( 0,sizeof($banners)-1 );
		$banner=$banners[$b];

		$nonunique=$DB->GetOne( "SELECT id FROM banner_views WHERE date>NOW()-'24 hours'::interval AND
			b_id=".$DB->qstr($banner['b_id'])." AND ip=".$DB->qstr(BannerHelper::getIP()) );

		if( !$nonunique )
		{
			$DB->Execute( "select insert_stat(now()::timestamp,'".$banner['bc_id']."',1,0)" );
			$DB->Execute( "INSERT INTO banner_views (b_id,ip,date)
					VALUES(".$DB->qstr($banner['b_id']).",".$DB->qstr(BannerHelper::getIP()).",NOW())" );
		}

		return array(
					"type" 		=> $banner['b_pos'],
					"format" 	=> $banner['b_type'],
					"id" 		=> $banner['b_id'],
					"value" 	=> $banner['b_banner'],
					"link" 		=> "banners/".$banner['b_id']."/".$banner['bc_id'] );
	}

	/**
	 * Get list of the vertical banners
	 *
	 * @param String $section main|catalog|list  For main page/catalog pages and ad listing pages
	 * @param int $catid      Catalog identificator
	 */
	static public function displayVerticalBanners( $section, $catid )
	{
		global $DB,$LANG;
	//	$DB->debug=true;

		switch( $section )
		{
			case "main":
				$qSection=" is_main='t' ";
				break;
			case "catalog":
				$qSection=" is_catalog='t' ";
				break;
			case "list":
				$qSection=" is_list='t' ";
				break;
			default:
				return "misconfiguration";
		}


		if( isset($catid) ) 
		{
			$row=APC_GetRow( array("catalog",$catid,"cat_tree"), $DB, "SELECT cat_tree FROM catalog WHERE cat_id=$catid" );
			$cat_tree=$row[ 'cat_tree' ];
			$qTree=" (bc_ltree @> '$cat_tree'::ltree) ";
			$catid="'$catid'";
		}
		else // only MAIN PAGE(s)
		{
			$qTree=" bc_cat_id IS NULL ";
			$catid="NULL";
		}

		$banners=APC_GetRows( array("banners",$LANG,7,$catid,$section), $DB,
			"SELECT * FROM v_banners WHERE b_pos='7' AND lang='$LANG' AND $qSection and $qTree
			 ORDER BY b_softid", -1 );

		$ret="";
		foreach( $banners as $data )
		{
			$DB->Execute( "select insert_stat(now()::timestamp,'".$data['bc_id']."',1,0)" );

			$ret.=BannerHelper::displayBanner( $data["b_pos"], 
								 $data["b_type"], 
								 $data["b_id"],
								 $data['b_banner'],
								 "banners/".$data['b_id']."/".$data['bc_id'],
								 $data["b_height"]
								 );
		}
		return $ret;
	}
}
