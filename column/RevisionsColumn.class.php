<?php

class RevisionsColumn extends BaseColumn 
{
	function __construct( $descr )
	{
		parent::__construct( "", $descr,true,NULL,false,"",false );
		$this->mySQL=false;
	}
	
	function getInput( &$request )
	{
		global $GLOBAL_PREFIX;
		
		return "<a href='".$GLOBAL_PREFIX."textRevisions/?text_id=$request[id]'>Show revisions</a>";
	}
}

?>
