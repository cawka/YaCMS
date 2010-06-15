<?php

require_once( BASEDIR . "/lib/Mail.class.php" );

class ReporterrorModel extends BaseModel
{
	private $error=array(
		1=>"Некорректное содержание объявления",
		2=>"Мошеничество",
		3=>"Неправильная контактная информация",
		4=>"Объявление помещено в неправильный раздел",
		5=>"В объявлении кто-то указал мои контактные данные",
		6=>"Другое",
	);

	public $myAdv;
	public $myReason;
	public $myReasonDescr="";
	public $myData="";

	public function sendReport( &$request )
	{
		global $SETTINGS, $DB;

		$reason=$request['reason'];
		$this->myAdv=$request['adv'];
		$this->myReason=$this->error[$reason];

		switch( $reason )
		{
		case 2:
			$this->myReasonDescr=$request['reason_text2'];
			break;
		case 6:
			$this->myReasonDescr=$request['reason_text'];
			break;
		default:
			break;
		}

		$this->myData=$DB->GetRow( "SELECT * FROM data where id=".$DB->qstr($this->myAdv) );

		$tmpl=new ReklamaUA( );
		$tmpl->caching=false;
		$tmpl->assign( "this", $this );

		Mail::sendToAdminFromUser( 'Anonymous', 'noreply@nobody.com', $SETTINGS['contacts_email'],
			'Abuse report', $tmpl->fetch('services/abuse_report.txt.tpl') );
	}
}

?>

