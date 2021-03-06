<?php

class TableController extends BaseController
{
	protected $myTemplate;
	protected $myTemplateForm;
	protected $myTemplateOne;
	
	public function __construct( &$model,&$helper,$template,$template_one,$template_form )
	{
		parent::__construct( $model,$helper );
		
		$this->myTemplate=$template;
		$this->myTemplateForm=$template_form;
		$this->myTemplateOne=$template_one;
	}
	
	public function index( &$tmpl,&$request )
	{
		global $LANG;

		$template=$this->myTemplate;
		if( isset($request['rss']) ) 
		{
			$template = str_replace('../', '', $this->myModel->myResultContent) . ".rss.xml";
			header ("Content-Type:text/xml; charset=utf-8" );
			//$template.=".rss.xml";
		}
	
		return $this->showTemplate( $tmpl, $request, $template, "collectData" );
	}
	
	public function show( &$tmpl,&$request )
	{
		global $LANG;

		if( isset($request['rss']) ) 
		{
			$template=$this->myTemplate;
			$template.=".rss.xml";
                        header ("Content-Type:text/xml; charset=utf-8" );
			return $this->showTemplate( $tmpl, $request, $template, "collectData" );
		}

		return $this->showTemplate( $tmpl, $request, $this->myTemplateOne, "getRowToShow" );
	}
	
	public function edit( &$tmpl, &$request )
	{
		if( isset($request['inner']) ) $tmpl->assign( "withouthead", "true" );

		return $this->showTemplate( $tmpl, $request, $this->myTemplateForm, "getRowToEdit" );
	}
	
	public function add( &$tmpl, &$request )
	{
		if( isset($request['inner']) ) $tmpl->assign( "withouthead", "true" );

		return $this->showTemplate( $tmpl, $request, $this->myTemplateForm, "getRowFromRequest" );
	}
	
	public function delete( &$tmpl,&$request )
	{
		DBHelper::connect( );
		$this->myModel->deleteRow( $request );
		$this->postDelete( $tmpl,$request );
	}
	
	
	public function save_add( &$tmpl,&$request )
	{
		DBHelper::connect( );
		$status=$this->myModel->validateSave( $request );
		if( $status!=""  )
		{
			$request['error']=$status;
			$this->add( $tmpl, $request );
			exit( 0 );
		}
		$this->myModel->save_add( $request );
		
		$this->postSave( $tmpl,$request );
	}
	
	public function save_edit( &$tmpl,&$request )
	{
		DBHelper::connect( );
		$status=$this->myModel->validateSave( $request );
		if( $status!="" )
		{
			$request['error']=$status;
			$this->edit( $tmpl, $request );
			exit( 0 );
		}
		$this->myModel->save_edit( $request );

		$this->postSave( $tmpl,$request );
	}

	protected function postSave( &$tmpl, &$request )
	{
		$tmpl->clearAllCache( );
		return parent::postSave( $tmpl, $request );
	}

	protected function postDelete( &$tmpl, &$request )
	{
		$tmpl->clearAllCache( );
		return parent::postDelete( $tmpl, $request );
	}
}

