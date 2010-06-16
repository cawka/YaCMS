<?php

class KeywordsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/keywords.tpl","",""
		);
	}
}

?>
