<?php

class TableBaseModel extends BaseModel
{
	public $myElementsPerPage=30;
	public $myElementOffset=0;
	public $myElementCount=0;
	public $myIsOffset=false;

	public $myMaxElementCount=1000;

	public $myParentId="frame_";

	public function __construct( $php, $offsets=false )
	{
		parent::__construct( $php );
		$this->myIsOffset=$offsets;
	}

	public function createSQL( )
	{
		$ret="CREATE TABLE $this->myTableName ( \n";
		if( !isset($this->myColumns[$this->myId]) )
		{
			$ret.="\t$this->myId integer primary key not null auto_increment\n";
			$i=1;
		}
		else
			$i=0;

		foreach( $this->myColumns as $column )
		{
			if( !$column->mySQL ) continue;

			if( $i>0 ) 
				$ret.="\t, ";
			else
				$ret.="\t";
			$ret.="$column->myName\t".$column->getSQLType()."\n";

			$i++;
		}
		if( isset($this->myColumns[$this->myId]) )
			$ret.="\t, PRIMARY KEY($this->myId)\n";
		$ret.=");\n";

		return $ret;
	}

	public function getAddCtrl( $name=NULL )
	{
		global $Auth; if( !$Auth->isAllowed("add") ) return "";

		if( !isset($name) ) $name=$this->myHelper->img_button("new","Add");

		return $this->myHelper->link_popup( $this,"add",
											$name,
											"Add",
											$this->getColumnParams( ) );
	}

	public function getEditCtrl( &$row, $name=NULL )
	{
		global $Auth; if( !$Auth->isAllowed("edit") ) return "";

		if( !isset($name) ) $name=$this->myHelper->img_button("edit","Edit");

		return $this->myHelper->link_popup(
						$this,"edit",
						$name,
						"Edit",
						$this->getColumnParams( array($this->myId=>$row[$this->myId]) ) );
	}

	public function getDeleteCtrl( &$row )
	{
		global $Auth; if( !$Auth->isAllowed("delete") ) return "";

		return $this->myHelper->link_popup_confirm(
						$this,"delete",
						$this->myHelper->img_button("delete","Delete"),
						$this->getColumnParams( array($this->myId=>$row[$this->myId]) ),
						"Are you sure?" );
	}

	public function getCurPage( &$request,$count )
	{
		$this->myElementCount=$count;
		$page=$request['pp'];
		if( !isset($page) || !is_numeric($page) || $page<0 ) { $this->myElementOffset=0; return 0; }

//		if( $page*$this->myElementsPerPage >= $this->myElementCount )
//			$this->myElementOffset=floor( ($this->myElementCount+$this->myElementsPerPage-1) / $this->myElementsPerPage )-1;
//		else
			$this->myElementOffset=$page;
		return $this->myElementOffset;
	}

	public function getPageOffset( $page,$offset,$suppress=true )
	{
		$test=$page+$offset;
		if( $test<0 ) return 0;

		if( !$suppress ) return $test;

		if( $test*$this->myElementsPerPage >= $this->myElementCount )
		{
			$ret=floor( ($this->myElementCount+$this->myElementsPerPage-1) / $this->myElementsPerPage )-1;
			//print "$ret<BR>";
			return $ret;
		}
		else
			return $test;
	}

	public function isMoreOffsets( )
	{
		return $this->myIsOffset && $this->myElementCount>$this->myElementsPerPage;
	}

	public function getPageOffsetCtrl( $suppress=true, $baseurl=NULL, $pagecount=NULL )
	{
		global $LANG,$langdata;
		$langdata=array(
			"navi_page"=>"Page",
		);

		if( $this->myElementCount<=$this->myElementsPerPage ) return ""; //no need to special navigation control
		$this->myElementOffset=$this->getPageOffset($this->myElementOffset,0,$suppress); //additional check

		$pages=array();
		if( $suppress )
		{
			$pages=array(
				$this->getPageOffset($this->myElementOffset,-6,$suppress),
				$this->getPageOffset($this->myElementOffset,-5,$suppress),
				$this->getPageOffset($this->myElementOffset,-4,$suppress),
				$this->getPageOffset($this->myElementOffset,-3,$suppress),
				$this->getPageOffset($this->myElementOffset,-2,$suppress),
				$this->getPageOffset($this->myElementOffset,-1,$suppress),
				$this->myElementOffset,
				$this->getPageOffset($this->myElementOffset,+1,$suppress),
				$this->getPageOffset($this->myElementOffset,+2,$suppress),
				$this->getPageOffset($this->myElementOffset,+3,$suppress),
				$this->getPageOffset($this->myElementOffset,+4,$suppress),
				$this->getPageOffset($this->myElementOffset,+5,$suppress),
				$this->getPageOffset($this->myElementOffset,+6,$suppress),
				$this->getPageOffset($this->myElementOffset,+7,$suppress),
				$this->getPageOffset($this->myElementOffset,+8,$suppress),
				$this->getPageOffset($this->myElementOffset,+9,$suppress),
			);
			$pages=array_unique( $pages );
		}
		else
		{
			if( !isset($pagecount) )
				$pagecount=1+floor($this->myElementCount/$this->myElementsPerPage,$suppress);

			for( $i=0; $i<$pagecount; $i++ ) array_push( $pages, $i );
		}

		$ret="<span class=\"pagesblockinside\">".$langdata['navi_page'].":\n";
		if( $this->myElementOffset>0 )
				$ret.="<a href=\"$url".
					BaseHelper::getRequest("pp",($this->myElementOffset-1>0)?"pp=".($this->myElementOffset-1):"" ).
					"\"><<</a>\n";

		foreach( $pages as $i )
		{
			if( $i==$this->myElementOffset )
				$ret.="<span class=\"selectedpage\">".($i+1)."</span>\n";
			else
				$ret.="<a href=\"".BaseHelper::getRequest("pp",($i>0)?"pp=$i":"")."\">".($i+1)."</a>\n";
		}
		if( $this->getPageOffset($this->myElementOffset,+1)!=$this->myElementOffset )
				$ret.="<a href=\"$url".
					BaseHelper::getRequest("pp","pp=".($this->myElementOffset+1) ).
					"\">>></a>\n";
		$ret.="</span>\n";
		return $ret;
	}

	public function getTableHeader( )
	{
		return "";
	}

	public function isId( )
	{
		return true;
	}
}
