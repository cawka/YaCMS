<?php

class TextRevisionsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/text_revisions.tpl","admin/text_revisions_show.tpl",""
		);
	}

	public function diff( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl,$request,"admin/text_revisions_diff.tpl","diff" );
	}
}

?>
