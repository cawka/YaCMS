<?php

class BookmarksHelper extends BaseTableThickBoxHelper
{
	static public function addToBookmarks( $id )
	{
		global $langdata,$DB;

		if( isset($_SESSION['bookmarks']) && in_array($DB->qstr( $id ),$_SESSION['bookmarks'])  )
		{
			return "<strong>записано в блокноте</strong>";
		}
		else
			return "<span class='favadd'>".
				   "<a href='#' onclick='return addToNotebook($id)'>$langdata[ad_add_bookmark]</a>".
				   "</span>";
	}
}
