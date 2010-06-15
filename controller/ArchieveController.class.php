<?php

class ArchieveController extends TableController 
{
	protected $myCachingEnabled=true;
	protected $myCacheLifetime=1604800;

	public function __construct( &$model,&$helper ) //index and show should be allowed to everybody
	{
		parent::__construct( $model,$helper,
			"ads/archieve-list.tpl","ads/archieve.tpl",""
		);
	}

    public function index( &$tmpl, &$request )
    {
        $this->myCacheId.="|".(isset($request['pp'])?$request['pp']:"0");

        return parent::index( $tmpl, $request );
    }


	public function show( &$tmpl, &$request )
	{
		$this->myCacheId=$request['id']; // this ID is already checked in model constructor
        if( isset($request['print']) ) $this->myCacheId.="|print";
		if( isset($request['popup']) ) $this->myCacheId.="|popup";

        return parent::show( $tmpl, $request );
	}
}

?>
