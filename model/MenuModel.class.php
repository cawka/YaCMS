<?php

class MenuModel extends TableModel
{
		public function __construct( $php, $parent_id=NULL )
		{
				global $DB;

				if( !isset($parent_id) )
				{
					$parent_id=$_REQUEST['parent_id'];
				}

//				$this->myParentId="menubar";
				$this->myParentId="TB_ajaxContent";

				parent::__construct( $DB, $php, "menu", array(
						new TextColumn("name","Menu item","Required"),
						new TextColumn("description","Description"),
						new TextColumn("link","Linked item"),
						new IntegerColumn("display_order","Display order"),
						new TextColumn("width","Main menu width"),
						new TextColumn("target","Target"),
						new HiddenColumn("parent_id",$parent_id ),
					)
				);
				$this->myOrder="display_order";
				$this->myRefreshAction="index";
		}
}

