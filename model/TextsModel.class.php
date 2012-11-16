<?php

class TextsModel extends TableModel
{
	public function __construct( $php, $page_id=NULL, $page_block=NULL )
	{
		global $DB;

		if( !isset($page_id) || !isset($page_block) )
		{
			$page_id=$_REQUEST['page_id'];
			$page_block=$_REQUEST['page_block'];
		}

		$this->myParentId="frame_$page_block";

		parent::__construct( $DB, $php, "texts", array(
			new RevisionsColumn( "Revisions" ),
			new ListColumn ("format", "Page format", NULL, array(""=>"html", "0"=>"markdown")),
			'textarea' => new TextAreaHTMLColumn("text","Content",NULL,false,""," ckhtml"),
			'page_id'=>new HiddenColumn("page_id",$page_id),
			'page_block'=>new HiddenColumn("page_block",$page_block), 
		)
		);

		$this->RefreshByReload=true;
		//				$this->myRefreshAction="show";
	}

	protected function rowId( $request )
	{
		if( isset($request['id']) ) return parent::rowId( $request );

		$ret="page_id=".$this->myDB->qstr($this->myColumns['page_id']->getValue()).
			" AND ".
			"page_block=".$this->myDB->qstr($this->myColumns['page_block']->getValue());
		return $ret;
	}

	public function getQuery( )
	{
		if( !isset($_REQUEST['id']) )
			$q=array( "page_id"=>$_REQUEST['page_id'], "page_block"=>$_REQUEST['page_block'] );
		else
			$q=array( "id"=>$_REQUEST['id'] );
		return http_build_query( $q,'','&amp;' );
	}


	//		protected function updateLastModifiedStatic( $static_id )
	//		{
	//			if( !isset($static_id) ) return;
	//			$this->myDB->Execute( "UPDATE static_pages SET lastmodified=NOW() WHERE id=".
	//										$this->myDB->qstr($static_id) ); 
	//		}

	public function save_add( &$request )
	{
		StaticPagesHelper::updateLastModifiedStatic( $request['page_id'] ); 
		return parent::save_add( $request );
	}

	public function save_edit( &$request )
	{
		StaticPagesHelper::updateLastModifiedStatic( $request['page_id'] );

		/**
		 * Simple procedure to check whether text was modified or not.
		 * For some reason it does not always work as intended 
		 */
		//			$ret=$this->myDB->GetOne( 
		//"SELECT h.id FROM (select * FROM texts_history t where text_id=".$this->myDB->qstr($request['id'])." order by modified desc limit 1) h
		//	JOIN texts t ON t.id=h.text_id
		//	WHERE t.id=".$this->myDB->qstr($request['id'])." and t.text=h.text" );
		//
		//			if( !$ret )
		//			{
		$this->myDB->Execute( "insert into texts_history (text_id,modified,text)
			values(".$this->myDB->qstr($request['id']).
			",NOW(), (SELECT text FROM texts where id=".
			$this->myDB->qstr($request['id'])."));" ); //save history
		//			}

		return parent::save_edit( $request );
	}

	public function createSQL( )
	{
		$this->myColumns[]=new DateTimeColumn( "lastmodified", "Last modified" );
		return parent::createSQL( );
	}
}

?>
