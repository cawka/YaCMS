<?php

include_once ("lib/markdown/markdown.php");

class MarkdownHelper
{
	public static function format ($text)
	{
		return Markdown ($text);
	}
}

