<?php

class BookmarksModel extends TableModel
{
	public function	 __construct( $php )
	{
		global $langdata, $DB;

		parent::__construct( $DB, $php, "data", array( 
		),"id", true );
		$this->myTitle=ucwords( $langdata['_title_bookmarks'] );
	}

	public function collectData( &$request )
	{
		global $LANG;
		if( !isset($_SESSION['bookmarks']) || sizeof($_SESSION['bookmarks'])==0 ) return;

		$where="WHERE flag_category IS NULL AND id IN (".implode(",",$_SESSION['bookmarks']).")";

		$res=$this->collectDataBaseRaw( $request, "*", $where, "" );
		$this->myData=$res;//->GetRows( );
	}

//////////////////////////////////////////////
//////////////////////////////////////////////

	public function addBookmark( $id )
	{
		global $DB;

		$id=$DB->qstr( $id );
		if( !isset($_SESSION['bookmarks']) || !in_array($id,$_SESSION['bookmarks']) )
		{
			$_SESSION['bookmarks'][]=$id;
		}
	}

	public function deleteBookmarks( $ids )
	{
		global $DB;
		foreach( $ids as $key => $value ) { $ids[$key]=$DB->qstr( $value ); }

		$_SESSION['bookmarks']=array_diff( $_SESSION['bookmarks'], $ids );
		if( sizeof($_SESSION['bookmarks'])==0 ) unset( $_SESSION['bookmarks'] );
	}
	
	public function deleteBookmark( $id )
	{
		return $this->deleteBookmarks( array($id) );
	}
}

?>
