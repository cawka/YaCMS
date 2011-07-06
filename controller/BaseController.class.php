<?php

class BaseController
{
	public $myModel;
	
	/**
	 * Use 'fetch' SMARTY method to build page
	 *
	 * @var bool
	 */
	public $myUseSmartyFetch=false;
	public $myHelper;
	public $myAction;

	protected $myCachingEnabled=false; // per-controller caching enabler/disabler
	protected $myCacheLifetime=300;

	protected $myCacheId=""; //should be set properly

	public function __construct( &$model,&$helper )
	{
		$this->myModel=&$model;
		$this->myHelper=&$helper;
		$this->myModel->myHelper=&$helper;

		$this->myHelper->myModel=&$this->myModel;
		$this->myHelper->myController=&$this;
	}

	protected function enableCache( &$tmpl, &$request )
	{
		//enable cache if controller is configured to
		if( $this->myCachingEnabled && !$this->myAuth->isAllowed('nocache') ) 
		{
			$this->myCacheId=$this->myModel->myPhp."|".$this->myAction."|".$this->myCacheId;
			
			$tmpl->caching=2;
			$tmpl->cache_lifetime=$this->myCacheLifetime;
		}
	}
	
	protected function showTemplate( &$tmpl, &$request, $template, $model_method="" )
	{
		$this->enableCache( $tmpl, $request );

		if( !is_file($tmpl->template_dir."/".$template) ) 
		{
			$tmpl->caching=false;
			$tmpl->assign( "error", "Template [$template] not found" );
			$template="common/error.tpl";
		}
		
		$tmpl->assign( "this", $this->myModel );
		if( !$tmpl->isCached($template,$this->myCacheId) )
		{
			DBHelper::connect( ); //connect only when not cached

			if( $model_method!="" ) call_user_func( array($this->myModel,$model_method), $request );
			$tmpl->assign( "this", $this->myModel );
		}

		if( !$this->myUseSmartyFetch )
			$tmpl->display( $template, $this->myCacheId );
		else
			return $tmpl->fetch( $template, $this->myCacheId );
	}
	
	protected function showTemplateDB( $tmpl, &$request, $static_page_id, $model_method="" )
	{
		global $LANG, $DB;
		$this->enableCache( $tmpl, $request );

		$template="common/base_static.tpl";
		
		if( !is_file($tmpl->template_dir."/".$template) ) 
		{
			$tmpl->caching=false;
			$tmpl->assign( "error", "Template [$template] not found" );
			$template="common/error.tpl";
		}
		
		$tmpl->assign( "this", $this->myModel );

		if( !$tmpl->isCached($template, $this->myCacheId) )
		{
			if( $model_method!="" ) call_user_func( array($this->myModel,$model_method), $request );

			///////////////////////////////////////////////
			$tmp=new StaticPagesController( new StaticPagesModel( "staticPages" ), new BaseTableThickBoxHelper( ) );
			$this->myModel->myStaticPage=$tmp->myModel;

			$params=array( "id"=>$static_page_id );
			$this->myModel->myStaticPage->getRowToShow( $params );
			///////////////////////////////////////////////
			
			$tmpl->assign( "this", $this->myModel );
		}

		if( !$this->myUseSmartyFetch )
			$tmpl->display( $template, $this->myCacheId );
		else 
			return $tmpl->fetch( $template, $this->myCacheId );
	}
	
	protected function postSave( &$tmpl,&$request ) 
	{ 
		if( !isset($request['ajax']) )
			$this->myHelper->closeWindow( ); 
		else 
			$this->showTemplate( $tmpl,$request,"common/smoothbox_close.tpl","" );
	}
	
	protected function postDelete( &$tmpl,&$request ) 
	{
		if( !isset($request['ajax']) )
			$this->myHelper->closeWindow( ); 
		else 
//			$this->showTemplate( $tmpl,$request,"common/smoothbox_close.tpl","" );
			$this->index( $tmpl,$request );
	}
}

?>
