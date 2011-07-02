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
			$this->mainContent->myAuth=new AuthHelper( "texts" );

			$this->menuContent=new TextsModel( "texts", $request['id'], 1 );
			$this->menuContent->myHelper=$this->myHelper;
			$this->menuContent->myAuth=$this->mainContent->myAuth;

			$empty_array=array( );
			$this->mainContent->getRowToShow( $empty_array );
			$this->menuContent->getRowToShow( $empty_array );

			parent::getRowToShow( $request );
	}

	public function createSQL( )
	{
		$this->myColumns[]=new DateTimeColumn( "lastmodified", "Last modified" );
		return parent::createSQL( );
	}
}

