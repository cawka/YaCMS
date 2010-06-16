<?php

include_once( BASEDIR . "/class/daisydiff/HTMLDiff.php" );

class TextRevisionsHelper extends BaseTableThickBoxHelper 
{
	public function diff( $from, $to )
	{
		$diff=new HTMLDiffer( );
		return $diff->htmlDiff( $from, $to );
	}
}

