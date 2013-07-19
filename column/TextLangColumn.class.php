<?php

class TextLangColumn extends TextColumn 
{
	var $myIsFromView;
	
	function __construct( $name,$descr,$required=NULL,$fromview=true,$brief=false,$brmsg="", $class="", $opt_msg="", $readonly=false, $opt="" )
	{
		$this->myIsFromView=$fromview;
		parent::__construct( $name, $descr, $required, $brief, $brmsg, $class, $opt_msg, $readonly, $opt );
	}

	/**
	 * Smart saving of multi-language data
	 * @param $request	array		Input request data
	 * @param $id		int			id in 'texts' table. If 0, new ID will be generated
	 * @param $name		string		Name of the field in the request data
	 * @param $arr_id	string		?
	 * @return int	id in 'texts' table
	 */
	protected function saveTextData( $request, $id, $name, $arr_id=null )
	{
		global $DB,$LANGS;
		
		$texts=array();
		foreach( $LANGS as $lang_id=>$lang )
		{
			if( !$arr_id )
				$texts[$lang_id]=$DB->qstr( $request[$name.'_'.$lang] );
			else
				$texts[$lang_id]=$DB->qstr( $request[$name.'_'.$lang][$arr_id] );
		}
	
		if( $id==0 )
		{
			$id=$this->getNewTextId( );
			$this->insertTexts( $id, $texts );
		}
		else
			$this->updateTexts( $id, $texts );
			
		return $id;
	}	
	
	private function getNewTextId( )
	{
		global $DB;
		
		$id=$DB->GetOne( "SELECT MAX(t_id)+1 FROM texts" );
		if( !$id ) $id=1;
		return $id;
	}
	
	/**
	 * Insert multi-language (by default blank) record into the "texts" table
	 * @param $id		int		ID of the record to add
	 * @param $texts	array	[optional]  Array of the multi-language string
	 * @return void
	 */
	private function insertTexts( $id,$texts=null )
	{
		global $DB,$LANGS_rev;
		
		foreach( $LANGS_rev as $lang=>$lang_id )
		{
			if( isset($texts) )
				$sql="INSERT INTO texts (t_id,t_lang_id,t_text) VALUES($id,$lang_id,$texts[$lang_id])";
			else 
				$sql="INSERT INTO texts (t_id,t_lang_id,t_text) VALUES($id,$lang_id,'')";
			
			$DB->Execute( $sql );
		}
	}
	
	/**
	 * Update multi-language text data
	 * @param $id		int		ID in the texts table
	 * @param $texts	array	Array of the multi-language string
	 * @return void
	 */
	private function updateTexts( $id,$texts )
	{
		global $DB,$LANGS_rev;
		
		foreach( $LANGS_rev as $lang=>$lang_id )
		{
			$DB->Execute( "UPDATE texts SET t_text=$texts[$lang_id]
								WHERE t_id=$id AND t_lang_id=$lang_id" );
		}
	}
		

	function getUpdateName( )
	{
		return $this->myName.($this->myIsFromView?"_id":"");
	}
	
	function getInsert( $request )
	{
		global $DB;
		/// @bug 	I'm not sure whether length limitation required or not.
		
		$id=$this->saveTextData( $request, $request[$this->myName], $this->myName );
		return $DB->qstr( $id );
	}
	
	protected function getInputRealFront( $lang, $lang_id )
	{
		return "<img src='/images/$lang.gif' align='left' />";	
	}
	
	protected function getInputReal( $lang, $lang_id, $phrases, $row )
	{
		$classes=array("i");
		if( $this->myToolTip!="" ) array_push( $classes, "tooltip" );
		$classes=array_merge( $classes, $this->myValidate );
		
		return 
		"<input class=\"".implode(" ", $classes)."\" type='text'".
		" name='$this->myName"."_$lang' id='$this->myName"."_$lang'".
		" value='".htmlspecialchars($phrases[$lang_id],ENT_QUOTES,"UTF-8")."' />";
	}
	
	public function getInput( $row )
	{
		global $LANGS, $DB;
		
		$id=$row[$this->getUpdateName()];
		$ret="";
		if( isset($row[$this->myName]) )
		{
			$ret="<input type='hidden' name='".$this->myName."' value='$id' />";
		}

		$phrases=array();
		if( $id>0 ) $phrases=$DB->GetAssoc( "SELECT t_lang_id,t_text FROM texts WHERE t_id=$id" );
		
		foreach( $LANGS as $lang_id => $lang )
		{
			$ret.="<div>".$this->getInputRealFront( $lang, $lang_id );
			$ret.=$this->getInputReal( $lang, $lang_id, $phrases, $row )."</div>";
		}

		return $ret;			
	}
}
