<?php

class PriceTorgColumn extends GroupColumn
{
	function __construct( $name,$descr, $required=NULL,$brief=false,$brmsg="",$calc=true,$tooltip="",$tooltip_extra="" )
	{
		global $langdata;

		parent::__construct($name,$descr,array(
			"price"=>new PriceColumn($name,"",$required,$brief,$brmsg,"",$calc ),
			"torg"=>new BooleanColumn($name."_torg",$langdata['torg'],"",false,$langdata['torg_brief'] ),
		),true,$required,$brief);
		$this->myColumns["price"]->myToolTip=$tooltip."test";
		$this->myColumns["torg"]->myToolTip=$tooltip_extra."test2";

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
