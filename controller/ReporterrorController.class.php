<?php

class ReporterrorController extends BaseController 
{
	public function report( &$tmpl, &$request )
	{
		return $this->showTemplateDB( $tmpl, $request, "report/thanks.tpl","sendReport" ); 
	}
}

?>
