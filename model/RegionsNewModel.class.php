<?php

class RegionsNewModel extends TableModel 
{
	public function __construct( $php )
	{
		global $DB;
		
		parent::__construct($DB,$php,"regions",
			array(
			"reg_reg_id"=>new HiddenColumn(  "reg_reg_id",$_REQUEST['reg_reg_id']),
			"reg_name"=> new TextLangColumn( "reg_name","Название региона","Введите название региона",false),
			"reg_order"=> new IntegerColumn( "reg_order","Порядок вывода"),
			) ,"reg_id"
		);
		$this->myOrder="reg_order,reg_fullname";
	}
	
	public function collectData( &$request )
	{
		global $LANG;
		$this->myTableName=" (SELECT r.*,t.t_text as reg_fullname 
   FROM regions r
   JOIN texts t ON t.t_id = r.reg_name AND t.t_lang_id='$LANG') regions ";
		return parent::collectData( $request );
	}	
	
	public function denormalizeLanguages( )
	{
		$this->myDB->Execute( "select denormalize_regions()" );
	}

	public function getInputOptions( $request )
	{
		$column=new RegionsColumn( "reg_id", "", NULL, $request['levels'],false );
		return $column->getInput( $request );
	}
}
