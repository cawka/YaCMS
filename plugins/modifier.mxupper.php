<?php

function str_to_upper($str){
    return strtr($str,
    "abcdefghijklmnopqrstuvwxyz".
    "абвгдежзийклмнопрстуфхцчшщъыьэюя",
    "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
    "АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ");
}


function smarty_modifier_mxupper($string)
{
    return str_to_upper($string);
} 

?>