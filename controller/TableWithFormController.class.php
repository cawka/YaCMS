<?php
include_once( "TableController.class.php" );

class TableWithFormController extends TableController 
{
	public function show( &$request, &$tmpl )
	{
		return $this->index( $request, &$tmpl );
	}
	
	public function edit( &$request, &$tmpl )
	{
		return $this->index( $request, &$tmpl );
	}
}
