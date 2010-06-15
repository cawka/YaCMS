<?php

class AreaColumn extends GroupColumn 
{
	function __construct( $name,$descr,$req,$brief,$tooltip="",$tooltip_measure="" )
	{
		global $langdata;
		parent::__construct( $name,$descr,
			array(
				"value"=>new DoubleColumn($name."_num","",$req,false,"",NULL,0,"_phone" ),
				"measure" =>new ListColumn($name."_code","",$req,
					array(
						0=>$langdata['opt_m2'],
						1=>$langdata['opt_sotok'],
						2=>$langdata['opt_ga'],
					),
					false,"","_phone" ),
			),
			true,$req,$brief );
		$this->myExtractDelimeter="&nbsp;";
		$this->myColumns["value"]->myToolTip=$tooltip;
		$this->myColumns["measure"]->myToolTip=$tooltip_measure;
	}
	
	function extractValue( &$row )
	{
		if( $this->myColumns["value"]->extractValue($row)!="" )
			return parent::extractValue( $row );
		else
			return "";
	}

	function extractBriefValue( &$row )
	{
		if( $this->myColumns["value"]->extractBriefValue($row)!="" )
			return parent::extractBriefValue( $row );
		else
			return "";
	}

	function extractPreviewValue( &$row )
	{
		if( $this->myColumns["value"]->extractPreviewValue($row)!="" )
			return parent::extractPreviewValue( $row );
		else
			return "";
	}
	
	function extractAdminValue( &$row )
	{
		if( $this->myColumns["value"]->extractAdminValue($row)!="" )
			return parent::extractAdminValue( $row );
		else
			return "";
	}
}

?>
