<?php

class ReIndexHelper 
{
	var $myTable;
	var $myAdv;
	
	function reIndexRange( $limit=1,$offset=0, $brief=false,$path=false,$search=false )
	{
		global $DB;
		
		$res=$DB->SelectLimit( "SELECT id,cat_id FROM data ORDER BY id DESC", $limit,$offset );
		
		$this->reIndex( $res,$brief,$path,$search );
	}
	
	function reIndexOne( $id,$brief=false,$path=false,$search=false )
	{
		global $DB;
		
		$res=$DB->Execute( "SELECT id,cat_id FROM data WHERE id='$id'" );
		
		$this->reIndex( $res,$brief,$path,$search );
	}
	
	function reIndex( &$res, $isBrief=false,$isPath=false,$isSearch=false )
	{
		global $DB, $LANG;
		
		foreach( $res as $data )
		{
			$this->myAdv=new AdsModel( "ads", $data['cat_id'] );

			$fake_request=array("id"=>$data["id"]);
			$this->myAdv->save_stage3( $fake_request,false );
		}
	}
}
