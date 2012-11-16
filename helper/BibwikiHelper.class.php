<?php
/**
 * Most of the code was borrowed from BibWiki implementation
 *
 */

include_once( "lib/OSBiB/format/BIBFORMAT.php" );
require_once( "lib/OSBiB/format/bibtexParse/PARSEENTRIES.php" );

class BibwikiHelper extends BaseTableThickBoxHelper 
{
	private $bibformat;

	function __construct( )
	{
		global $SETTINGS;
		$style="ieee2";
		if( isset($SETTINGS["bibwiki.style"]) ) $style=$SETTINGS["bibwiki.style"];

		$this->bibformat=new BIBFORMAT( CMSDIR . "/lib/OSBiB", TRUE );
		list($info, $citation, $footnote, $styleCommon, $styleTypes) =
		$this->bibformat->loadStyle( CMSDIR . "/lib/OSBiB/styles/bibliography/", $style );
		$this->bibformat->getStyle( $styleCommon, $styleTypes, $footnote );

//		print_r( $styleTypes );
//		die;
//		$this->bibformat->cleanEntry=TRUE;
		unset( $info, $citation, $footnote, $styleCommon, $styleTypes );
	}

	public function format_reference( &$entry )
	{
		global $PREFIX;
			$parse = NEW PARSEENTRIES( );
			$parse->expandMacro = TRUE;
			$parse->fieldExtract = TRUE;
			$parse->removeDelimit = TRUE;
			$parse->loadBibtexString( $entry );
			$parse->extractEntries( );

			list($preamble, $strings, $entries) = $parse->returnArrays();

			foreach( $entries as &$entry ) $entry=preg_replace( "/{|}/","",$entry );
			$this->bibformat->preProcess( $entries[0]['bibtexEntryType'], $entries[0] );
			$ret=$this->bibformat->map(). " ".$entries[0]['note'];
			if( isset($entries[0]['url']) && $entries[0]['url']!="" )
			{
				$ret="<a href=\"".$entries[0]['url']."\" target=\"_blank\"><img src='$PREFIX/images/extensions/".FileHelper::extension($entries[0]['url'],"html").".png' style='margin:0 5px 0 0; ' /></a> $ret";
			}
			unset( $parse );

			return $ret;
	}
	
	function parseAuthors( $authors )
	{
		$found_authors=array();
		$authors = explode(" and ", $authors );
		foreach( $authors as $a )
		{
			if( $a != "" and $a != "others" )
			{
    			$rv = $this->bwParseAuthor( $a );

    			if ($rv["firstname_initial"] != "")
					array_push( $found_authors, array( "n"=> $rv["surname"].", ".$rv["firstname_initial"].".", "f"=>$rv) );
				else
					array_push( $found_authors, array( "n"=>$rv["surname"], "f"=>$rv) );
			}
		}
		return $found_authors;
	}
	

	/**
	 *  Parses the elements of a BibTeX name.
	 *
	 *	Structure of returned array:
	 *  <code>
	 *  array(
	 *      "firstname" => 	           First christian name
	 *	    "firstname_initial" =>     Initial of the first christian name ("Danyé Ben Rubín" => "D")
	 *	    "firstname_simplified" =>  Simplified first christian name ("Danyé Ben Rubín" => "Danye")
	 * 	    "firstnames" =>            All christian names ("Danyé Ben Rubín" => "Danyé Ben")
	 * 	    "firstnames_simplified" => All simplified christian names ("Danyé Ben Rubín" => "Danye Ben")
	 *	    "firstnames_initials" =>   Initials of the christian names ("Danyé Ben Rubín" => "DB")
	 *	    "middlepart" =>            Middle part of the name
	 *	    "middlepart_simplified" => Simplified middle part
	 *	    "surname" =>               Surname ("Danyé Ben Rubín" => "Rubín")
	 *	    "surname_simplified" =>    Simplified name ("Danyé Ben Rubín" => "Rubin")
	 *  )
	 *  </code>
	 *
	 *  @param string
	 *  @return array
	 */
	function bwParseAuthor($val) {
	
		#print ("\n\n".$val."<br>\n");
		#print ("\n\n".bwDiacriticsSimplify($val)."<br>\n");
	
		$rv = array();
		$val = trim($val);
		$rv["firstname"] = "";
		$rv["firstname_simplified"] = "";
		$rv["firstname_initial"] = "";
		$rv["firstnames"] = "";
		$rv["firstnames_simplified"] = "";
		$rv["firstnames_initials"] = "";
		$rv["middlepart"] = "";
		$rv["middlepart_simplified"] = "";
		$rv["surname"] = "";
		$rv["surname_simplified"] = "";
	
		if (strpos($val, ",") !== false) {
			$parts = explode(",", $val, 2);
			$rv["surname"] = trim($parts[0]);
			$rv["surname"] = str_replace("{", "", $rv["surname"]);
			$rv["surname"] = str_replace("}", "", $rv["surname"]);
			$parts = $this->bwSplitName($parts[1]);
			foreach($parts as $p) {
				if ($this->bwIsUpper($p)) {
					if ($rv["firstname"] == "") {
						$rv["firstname"] = $p;
						$rv["firstname_initial"] = $p[0];
					}
					if ($rv["firstnames"] != "") $rv["firstnames"] .= " ";
					$rv["firstnames"] .= $p;
					$rv["firstnames_initials"] .= $p[0];
				}
				else {
					if ($rv["middlepart"] != "") $rv["middlepart"] .= " ";
					$rv["middlepart"] .= $p;
				}
			}
		} else {
			$parts = $this->bwSplitName($val);
			$last_part = array_pop($parts);
			$in_middlepart = false;
			$middlepart_done = false;
	
			foreach($parts as $p) {
				if ($this->bwIsUpper($p)) {
					if ($in_middlepart) {
						$in_middlepart = false;
						$middlepart_done = true;
					}
					if ($middlepart_done) {
						if ($rv["surname"] != "") $rv["surname"] .= " ";
						$rv["surname"] .= $p;
					} else {
						if ($rv["firstname"] == "") {
							$rv["firstname"] = $p;
							$rv["firstname_initial"] = $p[0];
						}
						if ($rv["firstnames"] != "") $rv["firstnames"] .= " ";
						$rv["firstnames"] .= $p;
						$rv["firstnames_initials"] .= $p[0];
					}
				}
				else {
					if ($middlepart_done) {
						if ($rv["surname"] != "") $rv["surname"] .= " ";
						$rv["surname"] .= $p;
					}
					else {
						if ($rv["middlepart"] != "") $rv["middlepart"] .= " ";
						$rv["middlepart"] .= $p;
						$in_middlepart = true;
					}
				}
			}
			if ($rv["surname"] != "") $rv["surname"] .= " ";
			$rv["surname"] .= $last_part;
		}
		
		$rv["surname_simplified"] =    $this->bwDiacriticsSimplify($rv["surname"]);
		$rv["firstname_simplified"] =  $this->bwDiacriticsSimplify($rv["firstname"]);
		$rv["firstnames_simplified"] = $this->bwDiacriticsSimplify($rv["firstnames"]);
		$rv["middlename_simplified"] = $this->bwDiacriticsSimplify($rv["middlename"]);
	
		#print (htmlentities($rv["surname"])."<br>");
		#print ($rv["surname_simplified"]."<br>");
		return $rv;
	}	
	
	/**
	 * Splits a BibTeX name in its pieces.
	 *
	 * <code>
	 * "Wolfgang F. Plaschg" --> |Wolfgang| |F.| |Plaschg|
	 * "Wolfgang {de la} Plaschg" --> |Wolfgang| |de la| |Plaschg|
	 * </code>
	 */
	function bwSplitName($val) {
		$rv = array();
		if (strpos($val, "{") === false)
			return preg_split("/\s+/", trim($val));
		else {
			while (strpos($val, "{") !== false and 
			       strpos($val, "}") > strpos($val, "{")) {
				$pos = strpos($val, "{");
				$part = substr($val, 0, $pos);
				$val = substr($val, $pos, strlen($val)-$pos);
				$pos = strpos($val, "}");
				$part2 = substr($val, 1, $pos-1);
				$val = substr($val, $pos+1, strlen($val)-$pos);
				
				$tmp = preg_split("/\s+/", trim($part));
				foreach($tmp as $t) if (empty($t) == false) $rv[] = $t;
				$rv[] = $part2;
			}
			$tmp = preg_split("/\s+/", trim($val));
			foreach($tmp as $t) if (empty($t) == false) $rv[] = $t;
		}
		return $rv;
	}
	
	function bwIsUpper($val) {
		if (preg_match("/[A-ZÄÖÜÁÉÍÓÚÀÂÃÅÇËÌÍÎÏÐÑÒÔÕÖØÙÚÛÜÝŒŠŸ]+/", $val[0])) return true;
		return false;
	}	
	
	
	/**
	 * @todo rewrite
	 */
	function bwDiacriticsSimplify($val) {
		$rep = array(
			"ä" => "a",
			"ö" => "o",
			"ü" => "u",
			"Ä" => "A",
			"Ö" => "O",
			"Ü" => "U",
			"ß" => "ss",
			"á" => "a",
			"é" => "e",
			"í" => "i",
			"ó" => "o",
			"ú" => "u",
			"Á" => "A",
			"É" => "E",
			"Í" => "I",
			"Ó" => "O",
			"Ú" => "U",
			"à" => "a",
			"è" => "e",
			"ì" => "i",
			"ò" => "o",
			"ù" => "u",
			"À" => "A",
			"È" => "E",
			"Ì" => "I",
			"Ò" => "O",
			"Ù" => "U",
			"â" => "a",
			"ê" => "e",
			"î" => "i",
			"ô" => "o",
			"û" => "u",
			"Â" => "A",
			"Ê" => "E",
			"Î" => "I",
			"Ô" => "O",
			"Û" => "U",
			"Æ" => "AE",
			"Ç" => "C",
			"Ë" => "E",
			"Ï" => "I",
			"Ð" => "D",
			"Ñ" => "N",
			"Õ" => "O",
			"Ø" => "O",
			"Ý" => "Y",
			"ã" => "a",
			"å" => "a",
			"æ" => "ae",
			"ç" => "c",
			"ë" => "e",
			"ð" => "o",
			"ñ" => "n",
			"õ" => "o",
			"ø" => "o",
			"ý" => "y",
			"ÿ" => "y",
			"Ā" => "A",
			"ā" => "a",
			"ă" => "a",
			"Ă" => "A",
			"Ą" => "A",
			"ą" => "a",
			"Ć" => "C",
			"ć" => "c",
			"Ĉ" => "C",
			"ĉ" => "c",
			"Ċ" => "C",
			"ċ" => "c",
			"Č" => "C",
			"č" => "c",
			"Ď" => "D",
			"ď" => "d",
			"Đ" => "D",
			"đ" => "d",
			"Ē" => "E",
			"ē" => "e",
			"Ĕ" => "E",
			"ĕ" => "e",
			"Ė" => "E",
			"ė" => "e",
			"Ę" => "E",
			"ę" => "e",
			"Ě" => "E",
			"ě" => "e",
			"Ĝ" => "G",
			"ĝ" => "g",
			"Ğ" => "G",
			"ğ" => "g",
			"Ġ" => "G",
			"ġ" => "g",
			"Ģ" => "G",
			"ģ" => "g",
			"Ĥ" => "H",
			"ĥ" => "h",
			"Ħ" => "H",
			"ħ" => "h",
			"Ĩ" => "I",
			"ĩ" => "i",
			"Ī" => "I",
			"ī" => "i",
			"Ĭ" => "I",
			"ĭ" => "i",
			"Į" => "I",
			"į" => "i",
			"İ" => "I",
			"ı" => "i",
			"Ĳ" => "IJ",
			"ĳ" => "ij",
			"Ĵ" => "J",
			"ĵ" => "j",
			"Ķ" => "K",
			"ķ" => "k",
			"ĸ" => "k",
			"Ĺ" => "L",
			"ĺ" => "l",
			"Ļ" => "L",
			"ļ" => "l",
			"Ľ" => "L",
			"ľ" => "l",
			"Ŀ" => "L",
			"ŀ" => "l",
			"Ł" => "L",
			"ł" => "l",
			"Ń" => "N",
			"ń" => "n",
			"Ņ" => "N",
			"ņ" => "n",
			"Ň" => "N",
			"ň" => "n",
			"ŉ" => "n",
			"Ŋ" => "NJ",
			"ŋ" => "nj",
			"Ō" => "O",
			"ō" => "o",
			"Ŏ" => "O",
			"ŏ" => "o",
			"Ő" => "O",
			"ő" => "o",
			"Œ" => "OE",
			"œ" => "oe",
			"Ŕ" => "R",
			"ŕ" => "r",
			"Ŗ" => "R",
			"ŗ" => "r",
			"Ř" => "R",
			"ř" => "r",
			"Ś" => "S",
			"ś" => "s",
			"Ŝ" => "S",
			"ŝ" => "s",
			"Ş" => "S",
			"ş" => "s",
			"Š" => "S",
			"š" => "s",
			"Ţ" => "T",
			"ţ" => "t",
			"Ť" => "T",
			"ť" => "t",
			"Ŧ" => "T",
			"ŧ" => "t",
			"Ũ" => "U",
			"ũ" => "u",
			"Ū" => "U",
			"ū" => "u",
			"Ŭ" => "U",
			"ŭ" => "u",
			"Ů" => "U",
			"ů" => "u",
			"Ű" => "U",
			"ű" => "u",
			"Ų" => "U",
			"ų" => "u",
			"Ŵ" => "W",
			"ŵ" => "w",
			"Ŷ" => "Y",
			"ŷ" => "y",
			"Ÿ" => "Y",
			"Ź" => "Z",
			"ź" => "z",
			"Ż" => "Z",
			"ż" => "z",
			"Ž" => "Z",
			"ž" => "z",
			"ſ" => "f",
			"Ə" => "e",
			"ƒ" => "f",
			"Ơ" => "O",
			"ơ" => "o",
			"Ư" => "U",
			"ư" => "u",
			"Ǎ" => "A",
			"ǎ" => "a",
			"Ǐ" => "I",
			"ǐ" => "i",
			"Ǒ" => "O",
			"ǒ" => "o",
			"Ǔ" => "U",
			"ǔ" => "u",
			"Ǖ" => "U",
			"ǖ" => "u",
			"Ǘ" => "U",
			"ǘ" => "u",
			"Ǚ" => "U",
			"ǚ" => "u",
			"Ǜ" => "U",
			"ǜ" => "u",
			"Ǻ" => "A",
			"ǻ" => "a",
			"Ǽ" => "AE",
			"ǽ" => "ae",
			"Ǿ" => "O",
			"ǿ" => "o",
			"ə" => "e"
			# @todo add more...
		);	
		return str_replace(array_keys($rep), array_values($rep), $val);
	}
	
	public function formatBibTexEntry( $entry )
	{
		$record="@".$entry['bibtexEntryType'].'{'.$entry['bibtexCitation'].",\n";

		foreach( $entry as $key=>$value )
		{
			if($key != 'bibtexCitation' && $key != 'bibtexEntryType')
			{
				$record.="\t".$key.'='.$value.",\n"; 
			}
		}
		
		$record.='}';
		
		return $record;
	}
}

?>
