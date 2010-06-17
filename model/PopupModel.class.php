<?php

class PopupModel extends StaticPagesBaseModel
{
	public $mainContent;
	public $menuContent;
	
	public function __construct( $php )
	{

			parent::__construct( $php );
	}

	public function getRowToShow( &$request )
	{
			$this->mainContent=new TextsModel( "texts", $request['id'], 0 );
			$this->mainContent->myHelper=$this->myHelper;

			$empty_array=array();
			$this->mainContent->getRowToShow( $empty_array );

			parent::getRowToShow( $request );
	}
}

?>
