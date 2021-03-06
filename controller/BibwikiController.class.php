<?php

class BibwikiController extends TableController
{
	public function __construct( &$model, &$helper )
	{
		parent::__construct( $model,$helper,
							 "bibwiki/list.tpl", "bibwiki/show.tpl", "common/form.tpl" );
	}

/*	public function keywords( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "bibwiki_keywords.tpl", "prepareKeywords" );
	}

	public function authors( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "bibwiki_authors.tpl", "prepareAuthors" );
	}
 */

	protected function cacheId( &$request )
	{
		switch( $request['action'] )
		{
		case "bibtex":
			return "bibwiki|show|$request[id]|$request[ajax]";
			break;
		case "index":
		default:
			return "bibwiki|index|$request[biblio_type]";
			break;
		}
#		return "$request[action]|".(isset($request[$this->myModel->myId])?"|".$request[$this->myModel->myId]."":"");
	}

	public function tex( &$tmpl,&$request )
	{
	  return $this->showTemplate($tmpl, $request, "resume-bib.tpl", "collectData" );
	}

	public function exportbib( &$tmpl,&$request )
	{
      header ("Content-Type:text/plain; charset=utf-8" );
	  return $this->showTemplate($tmpl, $request, "bibwiki/export-bib.tpl", "collectData" );
	}

	public function exportreport( &$tmpl,&$request )
	{
      header ("Content-Type:text/plain; charset=utf-8" );
	  return $this->showTemplate($tmpl, $request, "bibwiki/export-report.tpl", "collectData" );
	}

	public function bibtex( &$tmpl, &$request )
	{
		$this->myCachingEnabled=true;
		$this->myCacheId=urlencode( $request[$this->myModel->myId] );

		return $this->showTemplate( $tmpl, $request, "bibwiki/show-bibtex.tpl", "getBibTex" );
	}

	public function add( &$tmpl, &$request )
	{
		$this->myModel->prepareFields( $request );
		return parent::add( $tmpl, $request );
	}

	public function fields( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "common/form.tpl", "prepareFields" );
	}

	public function import( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "common/form.tpl", "prepareImport" );
	}

	public function import_save( &$tmpl, &$request )
	{
		return $this->showTemplate( $tmpl, $request, "bibwiki/importstatus.tpl", "import" );
	}

	public function processEntries( &$tmpl, &$reqeust )
	{
		$this->myModel->processEntries();
		return $this->index( $tmpl, $request );
	}
}
