<?php

function smarty_function_begun_hack( $params )
{
	$the=&$params["this"];
	$begun_auto_pad=$params["begun_auto_pad"];
	$begun_block_id=$params["begun_block_id"];
	
	$row=array( "cat_tree"=>$the->myInfo['cat_tree'],
		"text_special"=>"<div id='begun_block_$begun_block_id'></div>",
		"tekst_special"=>"<div id='begun_block_$begun_block_id'></div>",
		"opisanie_special"=>"<div id='begun_block_$begun_block_id'></div>",
		"zhelaem_special"=>"<div id='begun_block_$begun_block_id'></div>",
		"reg_id"=>26,
	);
	
	$ret="";
	foreach( $the->myBriefCols as $colkey )
	{
		if( isset($the->myPictureColumns[$colkey]) )
		{
			$ret.="<td class='short_ann_img'>";//<a href='".$this->getShowRowHref($row)."' target='_blank'>";
			$ret.=$the->myColumns[$colkey]->extractBriefValue( $row );//."</a>";
		}
		else
		{
			$ret.="<td class='ann_short_items";
			if( $row['comm_bold']=='t' ) $ret.=" payed";
			$ret.="'>";
			$ret.=$the->myColumns[$colkey]->extractBriefValue( $row );
		}
		$ret.="</td>\n";
	}	
	return $ret;
}

?>
