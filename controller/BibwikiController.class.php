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

	public function bibtex( &$tmpl, &$request )
	{
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

?>
