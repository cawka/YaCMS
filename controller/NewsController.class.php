<?php

class NewsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/news.tpl","","common/form.tpl"
		);
	}
}

?>
