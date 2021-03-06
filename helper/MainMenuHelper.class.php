<?php

class MainMenuHelper
{
	public $myData;
	public $mySubData;

	public $mySelectedId;
	public $mySelectedTitle;

	public $myLink;

	public $myStart;

	public static function getMenu( $start )
	{
		$menu=new MainMenuHelper( $start );
		return $menu;
	}

	public function __construct( $start=NULL ) 
	{
		global $DB;

		$stuff=preg_split( "/\?/", $_SERVER['REQUEST_URI'] );
		$this->myLink=$stuff[0];

		$this->myStart=$start;
		$this->myData=$this->getMenuLevel( $start );

		if( isset($this->myData) )
		{
			foreach( $this->myData as $menu )
			{
				if( isset($menu['isselected']) && $menu['isselected'] ) 
				{
					$this->mySelectedId=$menu['id'];
					$this->mySelectedTitle=$menu['name'];
				}
			}
		}

//		print_r( $this );
	}

	private function getMenuLevel( $parent_id )
	{
		global $DB, $PREFIX;
//		if( $parent_id!=$this->myStart ) return NULL;

		$menu=APC_GetRows( array("menu",$parent_id), $DB,
			"SELECT * FROM menu WHERE parent_id".
			(!isset($parent_id)?" IS NULL":"=".$DB->qstr($parent_id)).
			" ORDER BY display_order,name",
			0 );

		if( sizeof($menu)==0 ) return NULL;

//		// change to memcached version
//		$res=$DB->Execute( "SELECT * FROM menu WHERE parent_id".
//				(!isset($parent_id)?" IS NULL":"=".$DB->qstr($parent_id)).
//				" ORDER BY display_order" );
//		$menu=$res->GetRows( );
//		if( !$menu ) return NULL;

		$sel=false;
		foreach( $menu as &$item )
		{
			$item['sublevel']=$this->getMenuLevel( $item['id'] );

			if( $this->myLink==$PREFIX.$item['link'] ||
		        (isset($item['sublevel']) && isset($item['sublevel'][0]['sel']))	)
			{
					$item['isselected']=true;
					if( !isset($parent_id) )
					{
							$this->mySubData=&$item['sublevel'];
					}
					$sel=true;
			}
		}
	//	print "Level, $parent_id, ".($selected?"1":"0").", ".($child_selected?"1":"0")."<br />";
		if( $sel ) $menu[0]['sel']=true;
		return $menu;
	}
	
/*	function getLink( $id )
	{
		return "$id/";
	}
	
	function isSelected( &$row )
	{
		return $this->mySelectedRow['id']==$_REQUEST['id'];
	}*/
}

?>
