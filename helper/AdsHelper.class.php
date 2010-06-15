<?php

class AdsHelper extends CatalogsHelper
{
	static public function generateUUID( )
	{
		global $DB;

		$is=true;
		while( $is )
		{
			$ret=md5( uniqid(rand(), true) );
			$is=$DB->GetOne( "SELECT long_id FROM data WHERE long_id='$ret'" );
		}
		return $ret;
	}

	static public function isBanned( &$request )
	{
		global $DB;
		
		//phase 1.IP check
		$ret=$DB->GetOne( "SELECT id FROM bans WHERE type='ip' AND value like ".$DB->qstr($_SERVER[REMOTE_ADDR]) );

		$phone=preg_replace( '/\D/', '', $request['phone_num'] );
	        $ret2=$DB->GetOne( "SELECT id FROM bans WHERE type='phone' AND regexp_replace(value,'\\D','','g')='$phone'" );

		if( $ret || $ret2 ) return true;
	}

	public function autorizeAd( &$request )
	{
		global $SETTINGS,$langdata,$DB;

		if( isAdmin() ) return true;
		if( AdsHelper::isBanned($request) )
		{
			$request['error']=$langdata['adv_banned'];
			return false;
		}

		$limit=$this->myModel->myInfo['cat_limit'];
		if( !isset($limit) || !isset($limit[0]) ) return true;
		$limit=$limit[0];

		$phone=preg_replace( '/\D/', '', $request['phone_num'] );
		$count=$DB->GetOne( "SELECT count(*) FROM data WHERE flag_category IS NULL AND publ_end>NOW() AND cat_id=".$DB->qstr($request['cat_id'])." AND regexp_replace(phone_num,'\\D','','g')='$phone'" );
		$count2=$DB->GetOne( "SELECT count(*) FROM data WHERE flag_category IS NULL AND publ_end>NOW() AND cat_id=".$DB->qstr($request['cat_id'])." AND from_ip='$_SERVER[REMOTE_ADDR]'::inet" );
		if( $count+$count2<$limit )
			return true;
		else
		{
			$this->myModel->requirePayment=true;
			return true;
		}
	}

	public function long_id2id( $long_id )
	{
		global $DB;
		if( !isset($long_id) ) return NULL;

		return $DB->GetOne( "SELECT id FROM data WHERE long_id=".$DB->qstr($long_id) );
	}

/////////////////////////////////////////////////////////////////////

	public function getBriefColsCount( )
	{
		return sizeof( $this->myModel->myBriefCols );
	}

	function getBriefColsHead( )
	{
		$ret="";
		foreach( $this->myModel->myBriefCols as $colkey )
		{
			if( isset($this->myModel->myPictureColumns[$colkey]) )
				$ret.="<td class='short_ann_img'>";
			else
				$ret.="<td class='ann_short_items'>";
			$ret.=$this->myModel->getSortHeaderLink( $colkey );
			$ret.="</td>\n";
		}
		return $ret;
	}

	function showBriefColsData( &$row )
	{
		$ret="";
		foreach( $this->myModel->myBriefCols as $colkey )
		{
			if( isset($this->myModel->myPictureColumns[$colkey]) )
			{
				$ret.="<td class='short_ann_img'>";
				$ret.=$this->myModel->myColumns[$colkey]->extractBriefValue( $row );//."</a>";
			}
			else
			{
				$ret.="<td class='ann_short_items";
				if( $row['comm_bold']=='t' ) $ret.=" payed";
				$ret.="'>";
				$ret.="<a target='_blank' class=\"rr\" href='/show-$row[id].html'>";
				$ret.=$this->myModel->myColumns[$colkey]->extractBriefValue( $row );
				$ret.="</a>";
			}
			$ret.="</td>\n";
		}
		return $ret;
	}

	public static function getTopAdvertisements( $cat, $reg, $limit=5,
											     $datatype="top", $noimage=true, $paid=false )
	{
		global $DB;

		if( $paid ) $paidWh=" AND comm_top='t' ";
		if( $cat!==null && $cat!="" ) $catWh=" AND cat_tree ~ '*.$cat.*' ";
		if( $reg!==null && $reg!="" ) $regWh=" AND reg_tree ~ '*.$reg.*' ";

		$sql="SELECT * FROM data d
		WHERE flag_category IS NULL $paidWh $catWh $regWh ";

		if( $noimage ) $sql.=" AND (brief_photo IS NOT NULL AND brief_photo!='')  ";

		if( $datatype=="top" )
			$sql.="ORDER BY publ_begin DESC";
		else
			$sql.="ORDER BY random()";
		$sql.=" LIMIT $limit";

//		return $sql;

		return APC_GetRows( array("ads-top",$cat,$reg,$limit,$datatype,$noimage,$paid),
					 $DB, $sql, 300 );
	}
}
