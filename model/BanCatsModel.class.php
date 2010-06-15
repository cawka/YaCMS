<?php

class BanCatsModel extends TableModel 
{
	public $myTitle="Разделы баннера";
	
	function __construct( $php )
	{
		global $DB;
		parent::__construct( $DB,$php,"ban_cat",array(
					"bc_b_id"		=>new HiddenColumn("bc_b_id", $_REQUEST["bc_b_id"] ),
					"bc_cat_id"		=>new CatalogColumn("bc_cat_id","Раздел","Вы должны выбрать раздел"),
					"bc_shows_allow"=>new TextColumn("bc_shows_allow","Количество показов" ),
					"bc_start"		=>new DateColumn("bc_start", "Дата начала показа баннера" ),
					"bc_end"		=>new DateColumn("bc_end", "Дата окончания показа баннера" ),
			),"bc_id"
		);

		$this->myOrder="bc_ltree";
	}

	// some hack to make CatalogColumn work
	public function save_add( &$request )
	{
		if( isset($request['cat_id']) ) $request['bc_cat_id']=$request['cat_id'];
		array_push( $this->myColumns, 
		    new HiddenExactValueColumn("bc_shows_ok",0) );
		return parent::save_add( $request );
	}
	
	public function save_edit( &$request )
	{
		if( isset($request['cat_id']) ) $request['bc_cat_id']=$request['cat_id'];
		array_push( $this->myColumns, 
		    new HiddenExactValueColumn("bc_shows_ok",0) );
		return parent::save_edit( $request );
	}
}
