<?php

class B2mController extends BaseController 
{
	public function payment( &$tmpl,&$request )
	{
		global $SITEURL;
		
		$ret=$this->myModel->doPayment( $request );

		header("Content-type:text/html; charset=cp1251");
		$out= $ret?
			'{"message":"Лицевой счет Reklama.com.ua пополнен на '.$this->myModel->myAmount.' грн.","status":true}':
			'{"message":"Пополнение лицевого '.$SITEURL.'} временно недоступно","status":true}';

		print iconv( 'utf-8','cp1251',$out );
	}
}

?>
