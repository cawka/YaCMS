<?php

class CatalogsModel extends TableModel
{
	public $myInfo;
	public $myCatId;
	
	public function	 __construct( $php )
	{
		global $DB,$LANG,$langdata;

		if( isAdmin() )
		{
			$cols=array(
				new TextLangColumn( "cat_name","Название категории","Введите название категории",false),
				new TextLangColumn( "cat_title","Title",NULL,false),
				new TextAreaColumn( "cat_description","Description"),
				new IntegerColumn(  "cat_order","Порядок вывода категории",NULL,false,"",0 ),
				new TextLangColumn( "cat_type_name","Название типа следующего подраздела (например, \"Рубрика\")",NULL,false),
				new BooleanColumn(  "cat_showinad", "Следующий подраздел включается в вывод объявления"),
				new ListDBColumn(   "cat_type","Тип элемента","Выберите тип элемента","cat_types","type_id","type_name" ),
				new PhotoColumn(    "cat_pict","Картинка к категории",NULL,67,38),
				new IntegerColumn(  "cat_limit", "Лимит объявлений в категории" ),
			);
		}
		else
			$cols=array();
		
		parent::__construct( $DB,$php, "catalog", $cols, "cat_id" );
		$this->myOrder="cat_order_ltree,cat_name";
		$this->myParentId="content";
	}

	public function setCatId( $cat_id, $redirect=true )
	{
		$this->myCatId=$cat_id;
		$this->myInfo=$this->myHelper->getInfo( $this->myCatId );
		if( sizeof($this->myInfo)==0 ) ErrorHelper::redirect( "/" );
		
		if( $redirect )
		{
			$this->myHelper->redirectIfContainsSubcatalogs( $this->myCatId, $this->myInfo );
			$this->myHelper->redirectIfAdsList( $this->myCatId, $this->myInfo );
		}

		if( !isset($this->myInfo['type_levels']) ) $this->myInfo['type_levels']=2;
		$this->myParentPath=$this->myHelper->getParentPath( $this->myCatId, $this->myInfo['cat_tree'] );

		/// @todo remove this stuff
		$this->makeCompat( );
	}

	protected function extraWhere( &$request )
	{
		$orig_where=parent::extraWhere( $request );
		
		$where=" nlevel(cat_tree)<=".($this->myInfo['cat_level']+$this->myInfo['type_levels']);
		if( isset($this->myCatId) ) $where.=" AND cat_tree ~'*.$this->myCatId.*{1,}' ";

		if( $orig_where!="" ) $orig_where.=" AND ";
		return $orig_where.$where;
	}

	protected function extraSelect( &$request )
	{
		$ret=parent::extraSelect( $request );
		$ret[]="nlevel(cat_tree)-".$this->myInfo['cat_level']." as level";

		return $ret;
	}

	public function collectData( &$request )
	{
		global $LANG;
		$this->myTableName="catalog_lang$LANG";

		parent::collectData( $request );
	}

//	public function getRowToEdit( &$request )
//	{
//		global $LANG;
//		$this->myTableName="catalog_lang$LANG";
//
//		return parent::getRowToEdit( $request );
//	}

	public function DenormalizeAfterSave( )
	{
		$this->myDB->Execute( "select denormalize_catalog()" );
	}

	public function getEditCtrl( &$row )
	{
		global $SETTINGS;
		if( isAdmin() && $SETTINGS['catalog_edit']=='true' )
			return parent::getEditCtrl( $row );
		else
			return " ";
	}

	public function getDeleteCtrl( &$row )
	{
		global $SETTINGS;
		if( isAdmin() && $SETTINGS['catalog_delete']=='true' )
			return parent::getDeleteCtrl( $row );
		else
			return " ";
	}

	public function getAddCtrl( )
	{
		global $SETTINGS;
		if( isAdmin() && $SETTINGS['catalog_edit']=='true' )
			return parent::getAddCtrl( );
		else
			return " ";
	}

	/// @todo Change this data in the database
	private function makeCompat()
	{
		if( $this->myInfo['cat_template']=="realestate.tpl" )
			$this->myInfo['cat_template']="ads.tpl";
		else if( $this->myInfo['cat_template']=="realestate_nomap.tpl" )
			$this->myInfo['cat_template']="ads-nomap.tpl";
	}

	public function getInputOptions( $request )
	{
		$column=new CatalogColumn( "cat_id", "", NULL, $request['levels'],false );
		return $column->getInput( $request );
	}
}
