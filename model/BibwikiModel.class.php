<?php

class BibwikiModel extends TableModel 
{
	protected $myFields;

	public function __construct( $php, $type=NULL )
	{
		global $DB;
//		$DB->debug=true;
//		print_r( $_SERVER );
		if( !isset($type) && isset($_REQUEST['biblio_type']) ) $type=$_REQUEST['biblio_type'];
		$this->myParentId="TB_ajaxContent";

		parent::__construct( $DB,$php,"bibwiki",array(
			"biblio_type"=>new HiddenColumn("biblio_type", $type ),
//			"biblio_type"=>new ListColumn("biblio_type","Section","required", array(
//				"books"=>"Books",
//				"book_chapters"=>"Chapters",
//				"articles"=>"Articles",
//				"service_reports"=>"Public Service Reports",
//				"patents"=>"Patents",
//				"public_reports"=>"Public Reports",
//				"techreports"=>"Technical Reports",
//				"presentations"=>"Presentations",
//				"press_releases"=>"Press Releases",
//				"white_papers"=>"White Papers",
//				"tutorials"=>"Tutorials",
//			) ),
			"bibtex"=>new BibtexTypeColumn( "bibtex", "Publication type" ),
			"pdf"=>new FileColumn( "pdf", "PDF" ),
			"slides"=>new FileColumn( "slides", "Slides in PDF or PPT format" ),
		) );

		$this->mySearchColumns=array(
				            array( "column"=>new BooleanColumn("pdf","Pdfs attached"), "type"=>"custom", "where"=>"(pdf IS NULL OR pdf='')" ),
					);
		
		$this->myOrder="date DESC";
		$this->RefreshByReload=true;

		$this->mySortColumns=array(
	            "date"=>array(
                       "asc"=>"date",
                       "desc"=>"date DESC",
				),
		);
	}

	public function collectData( &$request )
	{
//		$this->myStatic=new StaticPagesModel( "staticPages" );
//		$this->myStatic->myHelper=$this->myHelper;
//		$req=array( "id"=>"bibwiki-$request[biblio_type]" );
//		$this->myStatic->getRowToShow( $req );

//		$this->myColumns["biblio_type1"]->myValue=$request['biblio_type'];
		parent::collectData( $request );
	}

	public function prepareFields( &$request )
	{
		$this->myData=$request;
		$this->getFields( $request );
	}

	protected function getFields( &$request )
	{
		if( isset($this->myFields) ) return;

		switch( $request['bibtex'] )
		{
		case "article":
		default:
			$fields=array("author"=>array("required"=>true),
						  "title"=>array("required"=>true),
						  "journal"=>array("required"=>true),
						  "year"=>array("required"=>true),
						  "volume"=>array(),
						  "number"=>array(),
						  "pages"=>array(),
						  "month"=>array(),
						  "note"=>array(),
						  "url"=>array(),
				  );
				break;
		case "conference":
		case "inproceedings":
				$fields=array("author"=>array("required"=>true),
							  "title"=>array("required"=>true),
							  "booktitle"=>array("required"=>true),
							  "year"=>array("required"=>true),
							  "editor"=>array(),
							  "volume"=>array(),
							  "pages"=>array(),
							  "number"=>array(),
							  "organization"=>array(),
							  "series"=>array(),
							  "publisher"=>array(),
							  "address"=>array(),
							  "month"=>array(),
							  "note"=>array(),
							  "url"=>array(),
					  );
				break;
		case "book":
				$fields=array("title"=>array("required"=>true),
							  "publisher"=>array("required"=>true),
							  "year"=>array("required"=>true),
							  "author"=>array(),
							  "editor"=>array(),
							  "volume"=>array(),
							  "number"=>array(),
							  "series"=>array(),
							  "address"=>array(),
							  "edition"=>array(),
							  "month"=>array(),
							  "note"=>array(),
							  "url"=>array(),
					  );
				break;
		case "incollection":
				$fields=array("author"=>array("required"=>true),
							  "title"=>array("required"=>true),
							  "booktitle"=>array("required"=>true),
							  "publisher"=>array("required"=>true),
							  "year"=>array("required"=>true),
							  "editor"=>array(),
							  "volume"=>array(),
							  "number"=>array(),
							  "series"=>array(),
							  "type"=>array(),
							  "chapter"=>array(),
							  "pages"=>array(),
							  "address"=>array(),
							  "edition"=>array(),
							  "month"=>array(),
							  "note"=>array(),
							  "url"=>array(),
					  );
				break;
		case "patent":
				$fields=array("author"=>array("required"=>true),
							  "title"=>array("required"=>true),
							  "year"=>array(),
							  "month"=>array(),
							  "day"=>array(),
							  "type"=>array(),
							  "number"=>array(),
							  "filing_number"=>array(),
							  "nationality"=>array(),
							  "yearfiled"=>array(),
							  "monthfiled"=>array(),
							  "dayfiled"=>array(),
							  "note"=>array(),
							  "url"=>array(),
					  );
				break;
		case "techreport":
				$fields=array("author"=>array("required"=>true),
							  "title"=>array("required"=>true),
							  "institution"=>array("required"=>true),
							  "year"=>array("required"=>true),
							  "type"=>array(),
							  "number"=>array(),
							  "address"=>array(),
							  "month"=>array(),
							  "url"=>array(),
					  );
				break;
		case "misc":
				$fields=array("title"=>array(),
							  "howpublished"=>array(),
							  "author"=>array(),
							  "month"=>array(),
							  "year"=>array(),
							  "url"=>array(),
							  "note"=>array(),
					  );
				break;
		case "phdthesis":
				$fields=array("author"=>array("required"=>true),
							  "title"=>array("required"=>true),
							  "school"=>array("required"=>true),
							  "year"=>array("required"=>true),
							  "address"=>array(),
							  "month"=>array(),
							  "type"=>array(),
							  "note"=>array(),
							  "url"=>array(),
					  );
				break;
		case "raw":
				$fields=array();
				$this->myColumns['entry']=new TextAreaColumn( "entry","Entry" );
				break;
		}

		foreach( $fields as $key=>$value )
		{
			array_push( $this->myColumns, new TextColumn($key, $key, isset($value['required'])?"Required":NULL) );
		}
		$this->myFields=$fields;
	}
	
	private function qstr( $str )
	{
		return $this->myDB->qstr( str_replace(array("{","}"),array("",""),$str) );
	}
	
	public function processEntries( )
	{
		$res=$this->myDB->Execute( "SELECT * FROM $this->myTableName" );
		while( $data=$res->FetchRow() )
		{
			foreach( $data as $key => &$value )
			{
				$value=addslashes( $value );
			}
			
			$this->save_edit( $data );
		}
	}
	
	private function denormalizeData( $bibentry, &$request ) //only one entry is allowed here
	{
		$parse = NEW PARSEENTRIES();
		$parse->loadBibtexString( $bibentry );
		$parse->extractEntries( );
		$this->myEntry=$parse->entries[0];

		$request['bibtex']=$this->myEntry['bibtexEntryType'];
		
		$this->updateDenormalizedColumns( $request );
	}
	
	private function updateDenormalizedColumns( &$request )
	{
		foreach( $this->myEntry as $key=>&$value ) $value=substr( $value,1,strlen($value)-2 );

		if( !isset($this->myEntry['pdf']) && isset($this->myEntry['local-url']) )
		{
			$this->myEntry['pdf']=preg_replace( "|^.*/data/files/|","",$this->myEntry['local-url'] );
			$request['pdf']=$this->myEntry['pdf'];
		}
	
		if( isset($this->myEntry["year"]) && $this->myEntry["year"]!="" &&
			(1900<=$this->myEntry["year"] && $this->myEntry["year"]<=2200) )
		{
			$this->myColumns['date']=
				new HiddenExactValueColumn( "date",    $this->myEntry["year"]."-".
					$this->extractMonth( $this->myEntry["month"] )."-01" );
		}
	}

	private function preprocessData( &$request )
	{
		$this->getFields( $request );

		if( $request['bibtex']=='raw' )
		{
			$this->denormalizeData( $request[$this->myColumns["entry"]->myName], $request );
		}
		else
		{
			$entry="@$request[bibtex]{id";
			foreach( $this->myFields as $key=>$value )
			{
				if( $request[$key]!="" ) $entry.=",\n$key={".$request[$key]."}";
			}
			$entry.=",\ncomment={}}";

			$this->myColumns=array(
					$this->myColumns['bibtex'],
					$this->myColumns['pdf'],
					$this->myColumns['slides'],
					$this->myColumns['biblio_type'],
					new HiddenExactValueColumn( 'entry', $entry ),
					);

			if( isset($request['year']) && $request["year"]!="" &&
	            (1900<=$request["year"] && $request["year"]<=2200) )
			{
				$this->myColumns['date']=
					new HiddenExactValueColumn( "date",$request["year"]."-".
													   $this->extractMonth( $request["month"] )."-01" );
			}
			else
				unset( $this->myColumns['date'] );
		}
	}

	public function save_add( &$request )
	{
//		$this->myDB->debug=true;
		$this->preprocessData( $request );

//		StaticPagesHelper::updateLastModifiedStatic( "bibwiki-".$request['biblio_type'] );
		return parent::save_add( $request );
	}
	
	public function save_edit( &$request )
	{
		$this->preprocessData( $request );

//		StaticPagesHelper::updateLastModifiedStatic( "bibwiki-".$request['biblio_type'] );
		return parent::save_edit( $request );
	}

	public function getBibTex( &$request )
	{
		return $this->getRowToEdit( $request );
	}

	public function getRowToEdit( &$request )
	{
		parent::getRowToEdit( $request );
		$this->getFields( $this->myData );

        $parse = NEW PARSEENTRIES();
        $parse->loadBibtexString( $this->myData['entry'] );
		$parse->extractEntries( );

		list($preamble, $strings, $entries) = $parse->returnArrays();
		$entry=&$entries[0];

		foreach( $this->myFields as $key=>$value )
		{
			//if( isset($entry[$key]) ) 
			$this->myData[$key]=$entry[$key];
		}
	}
	
	public function prepareImport( &$request )
	{
		$this->myImplicitAction="import_save";
		$request['bibtex']="raw";
		$this->getFields( $request );
	}
	
	public function import( &$request )
	{
		$request['bibtex']="raw";
		$parse = NEW PARSEENTRIES();
		$parse->loadBibtexString( $request['entry'] );
		$parse->extractEntries( );

		foreach( $parse->entries as &$entry )
		{
				$type=$entry['bibtexEntryType'];
				$id=$entry['bibtexCitation'];
				unset( $entry['bibtexEntryType'] );
				unset( $entry['bibtexCitation'] );
				$e="@$type{id";
				foreach( $entry as $key=>$value )
				{
					$e.=",\n$key = $value";
				}
				$e.=",\n comment = {}}";

				$req=array( "bibtex"=>"raw", "entry"=>$e );
				$this->save_add( $req );
				
				print "$id<br />";
		}
		
		die;
//		$this->myDB->debug=true;
		//$this->myImportStatus=array();


		
/*		foreach( $parse->entries as &$this->myEntry )
		{
			$id=$this->myDB->GetOne( "SELECT id FROM $this->myTableName WHERE key=".$this->myDB->qstr($this->myEntry['bibtexCitation']) );
			if( isset($id) )
			{
				array_push( $this->myImportStatus, array("id"=>$id,"key"=>$this->myEntry['bibtexCitation'],"status"=>"Failed","reason"=>"Duplicate key") );
				continue;
			}
			
//			print( $this->myHelper->formatBibTexEntry($this->myEntry) );
			$this->updateDenormalizedColumns( );

			$id=parent::save_add( $request );
			
			array_push( $this->myImportStatus, array("id"=>$id,"key"=>$this->myEntry['bibtexCitation'],"status"=>"OK") );
		}*/
	}


	private function extractMonth( $item )
	{
		if( !isset($item) ) return "01";
		$space=strpos( $item,' ' );
		if( $space!==FALSE ) $item=substr( $item,0,$space );
		return isset($this->Months[trim($item,"{}")])?$this->Months[trim($item,"{}")]:"01";
	}

	private $Months=array(
		"January"=>"01",
		"February"=>"02",
		"March"=>"03",
		"April"=>"04",
		"May"=>"05",
		"June"=>"06",
		"July"=>"07",
		"August"=>"08",
		"September"=>"09",
		"October"=>"10",
		"November"=>"11",
		"December"=>"12",
	);	
	
	public function getQuery( )
	{
		return "";
	}

	public function createSQL( )
	{
//		unset( $this->myColumns['biblio_type1'] );
		return parent::createSQL( );
	}
}

?>
