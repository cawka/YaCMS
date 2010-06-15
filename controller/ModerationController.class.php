<?php

class ModerationController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"admin/moderate.tpl","",""
		);
	}
	
	public function commit( &$tmpl, &$request )
	{
		$this->myModel->commit( $request );
		return $this->index( $tmpl, $request );
	}
}

?>
