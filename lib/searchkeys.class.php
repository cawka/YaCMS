<?php

/***************************************************************************
 *
 *   Author   : Eric Sizemore ( www.secondversion.com & www.phpsociety.com )
 *   Package  : Search Keywords
 *   Version  : 1.0.4
 *   Copyright: (C) 2006 - 2007 Eric Sizemore
 *   Site     : www.secondversion.com & www.phpsociety.com
 *   Email    : esizemore05@gmail.com
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 ***************************************************************************/

class search_keywords
{
  /**
   * Holds the referer string
   *
   * @var string
   */
  var $referer;

  /**
   * Holds the name of the search engine
   *
   * @var string
   */
  var $search_engine;

  /**
   * Holds the keywords
   *
   * @var mixed
   */
  var $keys;

  /**
   * Holds the query & keyword seperator
   *
   * @var string
   */
  var $sep;

  /**
   * Constructor. Sets the referer and seperator.
   *
   * @param  void
   * @return void
   */
  function search_keywords()
  {
    $this->referer = '';
    $this->sep = '';

    if ($_SERVER['HTTP_REFERER'] OR $_ENV['HTTP_REFERER'])
      {
	$this->referer = urldecode(($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $_ENV['HTTP_REFERER']));
	$this->sep = (preg_match('/(\?q=|\?qt=|\?p=)/', $this->referer)) ? '\?' : '\&';
      }
  }

  /**
   * Gets the search engine and keywords.
   *
   * @param  void
   * @return array
   */
  function get_keys()
  {
    if (!empty($this->referer))
      {
	if (preg_match('/www\.google/', $this->referer))
	  {
	    // Google
	    preg_match("#{$this->sep}q=(.*?)(\&|$)#si", $this->referer, $this->keys);
	    $this->search_engine = 'Google';
	  }
	else if (preg_match('/(yahoo\.com|search\.yahoo)/', $this->referer))
	  {
	    // Yahoo
	    preg_match("#{$this->sep}p=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Yahoo';
	  }
	else if (preg_match('/search\.msn/', $this->referer))
	  {
	    // MSN
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'MSN';
	  }
	else if (preg_match('/bing\.com/', $this->referer))
	  {
	    // MSN
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Bing';
	  }
	else if (preg_match('/www\.alltheweb/', $this->referer))
	  {
	    // AllTheWeb
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'AllTheWeb';
	  }
	else if (preg_match('/(looksmart\.com|search\.looksmart)/', $this->referer))
	  {
	    // Looksmart
	    preg_match("#{$this->sep}qt=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Looksmart';
	  }
	else if (preg_match('/(askjeeves\.com|ask\.com)/', $this->referer))
	  {
	    // AskJeeves
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Ask.com';
	  }
	else if (preg_match('/(search\.lycos\.com)/', $this->referer))
	  {
	    // Lycos
	    preg_match("#{$this->sep}query=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Lycos';
	  }
	else if (preg_match('/(altavista\.com)/', $this->referer))
	  {
	    // AltaVista
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'AltaVista';
	  }	
	else if (preg_match('/(duckduckgo\.com)/', $this->referer))
	  {
	    // Duck Duck Go
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'Duck Duck Go';
	  }	
	else if (preg_match('/(yebol\.com)/', $this->referer))
	  {
	    // Yebol (China)
	    preg_match("#key=(.*?)\&#", $this->referer, $this->keys);
	    $this->search_engine = 'Yebol (China)';
	  }
	else if (preg_match('/(search\.aol\.com)/', $this->referer))
	  {
	    // AOL
	    preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
	    $this->search_engine = 'AOL';
	  }
	else
	  {
	    $this->keys = 'Not available';
	    $this->search_engine = 'Unknown';
	  }
	return array(
		     $this->referer,
		     (!is_array($this->keys) ? $this->keys : $this->keys[1]),
		     $this->search_engine
		     );
      }
    return array();
  }
}

?>
