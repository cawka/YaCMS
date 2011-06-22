<?php

function smarty_function_file_size( $params, &$smarty )
{
	$size=$params['size'];

	$sizes = Array(' bytes', 'Kbytes', 'Mbytes', 'GB', 'TB', 'PB', 'EB');
    $ext = $sizes[0];
    
    for( $i=1; (($i<count($sizes)) && ($size >= 1024)); $i++) 
    {
        $size = $size / 1024;
        $ext = '&nbsp;' . $sizes[$i];
    }
    
    return round($size, 1) . $ext;

}

?>
