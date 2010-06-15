<?php

class UserBannersModel extends TableModel
{
	public $myTitle="";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB,$php, "( "."
select ua.*,NULL as cat_id,-9999999 as cat_order
	from ( SELECT * from ban_cat JOIN banners ON b_id=bc_b_id WHERE b_user_id='$_SESSION[user]' AND bc_cat_id IS NULL ) ua
UNION
select ua.*,cat_id,cat_order
	from ( SELECT * from ban_cat JOIN banners ON b_id=bc_b_id WHERE b_user_id='$_SESSION[user]' ) ua JOIN catalog c ON c.cat_id=bc_cat_id
	order by cat_order
"." ) user_banners", array() );
//		$this->myOrder="cat_order";
//		$this->myElementsPerPage=30;
		
//		$this->mySortColumns=array( 
//			"login"=>array("asc"=>"u_login","desc"=>"u_login DESC"),
//		);
		
//		$this->mySearchColumns=array(
//			array( "column"=>new TextColumn("u_login","Логин содержит"),"type"=>"like" ),
//		);
	}
	
	public function getStat( &$request )
	{
//		$this->myDB->debug=true;
		$ok=$this->myDB->GetRow( "SELECT b_id FROM banners join ban_cat ON bc_b_id=b_id where bc_id='$request[bid]' AND b_user_id='$_SESSION[user]'" );
		if( $ok )
		{
			$this->myTableName="(
select 
	get_days,(CASE WHEN views IS NOT NULL THEN views ELSE 0 END) as views,(CASE WHEN hits IS NOT NULL THEN hits ELSE 0 END) as hits

	from 
	(SELECT * FROM get_days( date_trunc('day',(NOW()-interval '1 month'))::timestamp,NOW()::timestamp,'1 day'::interval) ) s
		LEFT JOIN
		(SELECT date_trunc('day',stat_period) as d,SUM(stat_views) as views, SUM(stat_hits) as hits
			FROM ban_cat_stat 
			WHERE stat_bc_id='$request[bid]' AND stat_period> NOW() - INTERVAL '1 month' 
			GROUP BY date_trunc('day',stat_period) ) s2  ON d=get_days
	ORDER BY get_days) user_banners_stat ";
		}
		$array=array();
		return $this->collectData( $array );
	}
}

?>
