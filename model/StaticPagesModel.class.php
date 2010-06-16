<?php

class StaticPagesModel extends StaticPagesBaseModel
{
	public $mainContent;
	public $menuContent;
	
	public function __construct( $php )
	{
			parent::__construct( $php );
			$this->RefreshByReload=true;
	}

	public function getRowToShow( &$request )
	{
//			$this->myDB->debug=true;
			$this->mainContent=new TextsModel( "texts", $request['id'], 0 );
			$this->mainContent->myHelper=$this->myHelper;

			$this->menuContent=new TextsModel( "texts", $request['id'], 1 );
			$this->menuContent->myHelper=$this->myHelper;

			$empty_array=array( );
			$this->mainContent->getRowToShow( $empty_array );
			$this->menuContent->getRowToShow( $empty_array );

			parent::getRowToShow( $request );
	}
}

?>
