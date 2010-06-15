<?php

class AdsController extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"ads/list.tpl","ads/single.tpl","ads/_add.tpl"
		);
	}

	public function index( &$tmpl, &$request )
	{
		BaseHelper::disablePOST( );
		//default cache lifetime 300 seconds
		/*
		 * Create cache entry only if:
		 * 1. There are no GET requests (besides the standard _m and action)
		 * 2. There a `lang` request
		 * 3. There a `pp` request and value is less or equeal to 10
		*/
		$restricted_requests=array( // rule set what is allowed
			 "pp" => ' !isset($value) || (0<=$value && $value <= 10)',
			 "withphoto" => ' !isset($value) ',
			 "sort" => ' !isset($value) ',
			 "desc" => ' !isset($value) ',
			 "reg_id" => ' !isset($value) ',
		);

		$this->myCachingEnabled=BaseHelper::enableCaching( $restricted_requests );
		$this->myCacheId=$this->myModel->myCatId;
		$this->myCacheId.="|".(isset($request['pp'])?$request['pp']:"0");

		return parent::index( $tmpl, $request );
	}

	public function show( &$tmpl, &$request )
	{
		BaseHelper::disablePOST( );

		$this->myCachingEnabled=true;//!isAdmin();
		$this->myCacheLifetime=86400; // 1-day caching (to update number of shows)
		$this->myCacheId=$request['id']; // this ID is already checked in model constructor
		if( isset($request['print']) ) $this->myCacheId.="|print";
		if( isset($request['popup']) ) $this->myCacheId.="|popup";

		return parent::show( $tmpl, $request );
	}


////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

	public function edit( &$tmpl, &$request )
	{
		if( !isset($request['uuid']) )
		{
			return $this->showTemplate( $tmpl, $request, "ads/_edit.tpl", "dataFromRequest" );
		}
		else
		{
			$request['id']=$this->myHelper->long_id2id( $request['uuid'] );

			$tmpl->assign( "disable_catalog", true );
			return parent::edit( $tmpl, $request );
		}
	}

	public function save_edit( &$tmpl,&$request )
	{
		$request['id']=$this->myHelper->long_id2id( $request['uuid'] );
		if( !$request['id'] ) ErrorHelper::get500( );

		return parent::save_edit( $tmpl,$request );
	}

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

	public function delete( &$tmpl, &$request )
	{
		if( !isset($request['uuid']) )
		{
			return $this->showTemplate( $tmpl, $request, "ads/_delete.tpl", "dataFromRequest" );
		}
		else
		{
			// to prevent two queries, we are disablying long_id2id, but
			// in the model, the same (but extended) action will be performed
			$request['id']=$this->myHelper->long_id2id( $request['uuid'] );

			$ret=$this->myModel->deleteRow( $request );
			switch( $ret )
			{
//			a) User screw up
			case "user":
				ErrorHelper::redirect( "/ads/delete" );
				break;
//			b) Admin has not confirmed delete
			case "admin":
				$tmpl->assign( "error", "<input type='checkbox' name='confirm_delete' value='1'> Внимание! <b>Это объявление платное</b> <i>(".implode(",",$str).")</i>. Подтверждаете удаление?" );
				$tmpl->assign( "uuid", $request['uuid'] );
				return $tmpl->display( "ads/_delete.tpl" );
				break;
			default:
				$redirect=urldecode(urldecode( $request['redirect'] ));
				if( $redirect=="" ) $redirect="/ad_deleted.html";

				ErrorHelper::redirect( $redirect );
				break;
			}
			if( isset($ret) )
			{
				
			}
		}
	}

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

	protected function postAdd( &$tmpl, &$request )
	{
	}

	protected function postSave( &$tmpl, &$request )
	{
		$this->myModel->save_stage3( $request );

		if( $this->myModel->AnnEditType!="new" )
		{
	//		$tmpl->clear_cache( NULL, $this->myModel->myPhp."|show|".$request['id'] );
	//		$tmpl->clear_cache( NULL, $this->myModel->myPhp."|index|".$request['cat_id'] );

			$this->myHelper->clearCachePath( array("ads","show",$request['id']) );
//			$this->myHelper->clearCache( ); // not sure, we should consider deleting only relevant cache entries
		}

		$this->myModel->postSave( $request );
	}

	protected function postDelete( &$tmpl, &$request )
	{
		//!!! we need to know catalog ID after we deleted this stuff !!!

	//	$tmpl->clear_cache( NULL, $this->myModel->myPhp."|show|".$request['id'] );
	//	$tmpl->clear_cache( NULL, $this->myModel->myPhp."|index|".$request['cat_id'] );

		$this->myHelper->clearCachePath( array("ads","show",$request['id']) );
//		$this->myHelper->clearCache( ); // not sure, we should consider deleting only relevant cache entries

		$this->myModel->postDelete( $request );
	}
}

