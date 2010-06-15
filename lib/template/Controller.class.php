<?php

class <Template>Controller extends TableController 
{
	public function __construct( &$model,&$helper )
	{
		parent::__construct( $model,$helper,
			"<template>.tpl","","common/form.tpl"
		);
	}
}

