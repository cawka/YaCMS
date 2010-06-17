<?php

include_once( "lib/daisydiff/HTMLDiff.php" );

class TextRevisionsHelper extends BaseTableThickBoxHelper 
{
	public function diff( $from, $to )
	{
		$diff=new HTMLDiffer( );
		return $diff->htmlDiff( $from, $to );
	}
}

