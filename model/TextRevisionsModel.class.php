<?php

class TextRevisionsModel extends TableModel
{
		public function __construct( $php )
		{
				global $DB;

				parent::__construct( $DB, $php, "texts_history", array(
						new HiddenColumn( "text_id", $_REQUEST['text_id'] ), 
					)
			);
			$this->myOrder="modified desc";
		}

		public function diff( &$request )
		{
			$this->myTableName=" texts_history h JOIN (select id as t_id,text as text_orig FROM texts) t ON t_id=h.text_id ";
			return $this->getRowToShow( $request );
		}

		public function createSQL( )
		{
			$this->myColumns[]=new DateTimeColumn( "modified", "" );
			$this->myColumns[]=new TextColumn( "text", "" );
			return parent::createSQL( );
		}
}

?>
