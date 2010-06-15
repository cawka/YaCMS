<?php

class PriceExtraColumn extends GroupColumn
{
	function __construct( $name,$descr,$extra=array(),$required=NULL,$brief=false,$brmsg="",$calc=true,$tooltip="",$tooltip_extra="" )
	{
		parent::__construct($name,$descr,array(
			"price"=>new PriceColumn($name,"",$required,$brief,$brmsg,"",$calc ),
			"extra"=>new ListColumn( $name."_extra","","",$extra,false,"","_other" ),
		),true,$required,$brief);
		$this->myColumns["price"]->myToolTip=$tooltip;
		$this->myColumns["extra"]->myToolTip=$tooltip_extra;

		$this->myExtractDelimeter=" ";
		$this->myBriefMsg=$brmsg;
	}

	function extractValue( &$row )
	{
		if( $this->myColumns["price"]->extractValue($row)!="" )
			return parent::extractValue( $row );
		else
			return "";
	}

	function extractBriefValue( &$row )
	{
		if( $this->myColumns["price"]->extractBriefValue($row)!="" )
			return parent::extractBriefValue( $row );
		else
			return "";
	}

	function extractPreviewValue( &$row )
	{
		if( $this->myColumns["price"]->extractPreviewValue($row)!="" )
			return parent::extractPreviewValue( $row );
		else
			return "";
	}

	function extractAdminValue( &$row )
	{
		if( $this->myColumns["price"]->extractAdminValue($row)!="" )
			return parent::extractAdminValue( $row );
		else
			return "";
	}
}
