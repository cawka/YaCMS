<?php

class BookmarksController extends BaseController
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper );
	}

	public function index( &$tmpl, &$request )
	{
		$this->showTemplate( $tmpl, $request, "ads/list_notebook.tpl", "collectData" );
	}

	public function add( &$tmpl, &$request ) // serve only via ajax
	{
		global $langdata;

		if( !isset($request['id']) || $request['id']=='' || preg_match('/\D/',$request['id']) )
		{
			ErrorHelper::get500( );
		}

		$this->myModel->addBookmark( $request['id'] );
		print "<b>добавлено в записную книжку</b>";
	}

	public function delete( &$tmpl, &$request )
	{
		if( !isset($request['id']) || $request['id']=='' || preg_match('/\D/',$request['id']) )
		{
			ErrorHelper::get500( );
		}
		
		$this->myModel->deleteBookmark( $request['id'] );
		print "<div class='deleted'><b>удалено из записной книжки</b></div>";
	}

	public function deleteAll( &$tmpl, &$request )
	{
		unset( $_SESSION['bookmarks'] );
		print "";
	}
}
