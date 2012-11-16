<?php

include_once ("lib/markdown/markdown.php");

class MarkdownHelper
{
	public function format ($text)
	{
		return Markdown ($text);
	}
}

