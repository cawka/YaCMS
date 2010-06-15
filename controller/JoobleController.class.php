<?php

class JoobleController extends BaseController 
{
	public function vacancies( &$tmpl, &$request )
	{
		header( "Content-type: text/xml; charset=utf-8" );
		return $this->showTemplate( $tmpl, $request, "jooble/vacancies.tpl", "getVacancies" );
	}

	public function resumes( &$tmpl, &$request )
	{
		header( "Content-type: text/xml; charset=utf-8" );
		return $this->showTemplate( $tmpl, $request, "jooble/resumes.tpl", "getResumes" );
	}
}

