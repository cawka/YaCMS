<?php

function smarty_compiler_teaser($tag_attrs, &$compiler)
{
    $_params = $compiler->_parse_attrs($tag_attrs);
    
    $ret="";
    if( isset($_params['limit']) )    $ret.="\$this->assign('limit',    {$_params['limit']});";
    if( isset($_params['catalog']) )  $ret.="\$this->assign('catalog',  {$_params['catalog']});";
    if( isset($_params['datatype']) ) $ret.="\$this->assign('datatype', {$_params['datatype']});";
    if( isset($_params['region']) )   $ret.="\$this->assign('region',   {$_params['region']});";

    return "\$this->assign(limit, {$_params['limit']});".
    	   "\$this->assign(catalog, {$_params['catalog']});".
    	   //"\$this->assign(datatype, {$_params['datatype']});".
    	   //"\$this->assign(region, {$_params['region']});";
    	   "";
}

?>
