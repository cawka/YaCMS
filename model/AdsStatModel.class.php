<?php

class AdsStatModel extends BaseModel
{
	public $myTitle="Ads Stat";
	private $grp;
	private $fld;
	
	public function __construct( $php )
	{
		parent::__construct( $php );
		
		$this->myColumns=array(
			"from"=>new DateColumn("from", "From"),
			"to"=>  new DateColumn("to", "To"),
		);
	}
	
	public function prepareData( &$request )
	{
		global $DB;
		
		$where=$this->formatPeriod( $request["from"], $request["to"] );
		$this->preFetch( $request );
		
		if( $this->grp!="" )
			$sql="SELECT count(*) as count,$this->fld $this->f1 ". 
					" FROM data $this->f2 $where AND flag_category IS NULL GROUP BY $this->grp ORDER BY $this->f3 count DESC";
		else
			$sql="SELECT count(*) as count FROM data $where AND flag_category IS NULL ORDER BY count DESC";
			
		$res=$DB->Execute( $sql );
		$this->myData=$res;//->GetRows( );
	}
	
	public function prepareXML( &$request )
	{
		global $DB;
//		$DB->debug=true;
		$where=$this->formatPeriod( $request["from"], $request["to"] );
		
		$sql="select 
		get_days::date as date,(CASE WHEN count IS NOT NULL THEN count ELSE 0 END) as count
		from 
		(SELECT * FROM get_days( '$request[from]'::timestamp,'$request[to]'::date,'1 day'::interval) ) s
			LEFT JOIN
			(SELECT to_char(publ_begin, 'YYYY-MM-DD') as date,count(*) as count
				FROM data
				$where AND flag_category IS NULL
				GROUP BY to_char(publ_begin, 'YYYY-MM-DD') 
				) s2  ON date::date=get_days::date
		ORDER BY get_days";
					
		$res=$DB->Execute( $sql );
		$this->myData=$res;//->GetRows( );	
	}
	
	private function preFetch( &$request )
	{
		$group=array();
		$field=array();
		if( $request["show_user"] ) 
		{
			array_push( $group,"d.user_id,u_fname||' '||u_lname,u_login,email_copy" );
			array_push( $field,"d.user_id,u_fname||' '||u_lname as user,u_login,email_copy as email" );
			
			$this->f1="";
			$this->f2="d LEFT JOIN users u ON d.user_id=u.user_id";
		}
		if( $request["show_ip"] )   
		{
			array_push( $group,"from_ip" );
			array_push( $field,"from_ip" );
			
			//$f1=" as from_ip ";
			//$f2="d LEFT JOIN ip_addresses ON ip_address >>= d.from_ip ";
		}
		if( $request["show_days"] ) 
		{
			$this->f3="to_char(publ_begin, 'YYYY-MM-DD') DESC,";
			
			array_push( $group,"to_char(publ_begin, 'YYYY-MM-DD') " );
			array_push( $field,"to_char(publ_begin, 'YYYY-MM-DD') as date " );
					
		}
		$this->grp=implode( ",",$group );
		$this->fld=implode( ",",$field );		
	}
	
	private function formatPeriod( &$from, &$to )
	{
		if( !isset($from) || $from=="" ) $from=date( "Y-m-d" );
		if( !isset($to) || $to=="" )   $to=date( "Y-m-d" );
		return "WHERE ('$from'<=publ_begin AND publ_begin<='$to'::date+'1 day'::interval)";
	}
}
