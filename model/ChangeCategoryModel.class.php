<?php

class ChangeCategoryModel extends TableModel
{
	public $myTitle="Изменение раздела объявления";
	
	public function	 __construct( $php )
	{
		global $DB,$langdata;
		
		parent::__construct( $DB, $php, "data", array(
				new CatalogColumn( "cat_id","Раздел каталога",NULL,-1 ),
				new HiddenType3Column( "old_cat_id","cat_id" ) ) );

		$this->RefreshByReload=true;
		$this->myParentId="TB_ajaxContent";
	}

	public function save_edit( &$req )
	{
		global $DB;

		//clear cache
		APC_ClearCaches( array("a",$req[$this->myId]) );

		if( $req['cat_id']==$req['old_cat_id'] ) return;

		$DB->Execute( "BEGIN" );

		$newtype=$DB->GetOne( "SELECT type_data FROM catalog_lang1 WHERE cat_id=".$DB->qstr($req[cat_id]) );

		$oldtype=$DB->GetOne( "SELECT type_data FROM catalog_lang1 WHERE cat_id=".$DB->qstr($req[old_cat_id]) );

		if( $newtype==$oldtype )
		{
			$DB->Execute( "UPDATE data SET cat_id=".$DB->qstr($req[cat_id])." WHERE id=".$DB->qstr($req[$this->myId]) );
			$DB->Execute( "SELECT update_catalog_product_count(".$DB->qstr($req[cat_id]).",+1)" );
			$DB->Execute( "SELECT update_catalog_product_count(".$DB->qstr($req[old_cat_id]).",-1)" );
		}
		else
		{
			$helper=new DataSaveRestoreHelper();

			$helper->saveData( $oldtype,$req[$this->myId] );

			$helper->myData["data"][0][1]       =$req['cat_id'];
			$helper->myData["data"][0]["cat_id"]=$req['cat_id'];

			$DB->Execute( "ALTER TABLE data DISABLE TRIGGER tr_data_ondelete" );
			$DB->Execute( "DELETE FROM data WHERE id=".$DB->qstr($req[$this->myId]) );
			$DB->Execute( "ALTER TABLE data ENABLE TRIGGER tr_data_ondelete" );

			$helper->restoreData( $oldtype, $newtype );
		}
		
		$reindex=new ReIndexHelper();
		$reindex->reIndexOne( $req[$this->myId],true,true,true );

		$DB->Execute( "COMMIT" );
	}
}
