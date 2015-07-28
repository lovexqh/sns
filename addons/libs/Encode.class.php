<?php

function isUTF8($str)
{
if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
{
return true;
}
else
{
return false;
}
}
define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));

define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));

define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));

define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));

define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));
class Encode_Core {

/**

* 文件分析方法来检查UNICODE文件，ANSI文件没有文件头，此处不分析

*/

private function detect_utf_encoding($text) {
$first2 = substr($text, 0, 2);
$first3 = substr($text, 0, 3);
$first4 = substr($text, 0, 3);
if ($first3 == UTF8_BOM) return 'UTF-8';

elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';

elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';

elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';

elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16';

}

/**

* 取得编码

* @param string $str

* @return string $encoding

*/

public static function get_encoding($str){

$ary = array();

$ary[] = "UTF-8";

$ary[] = "ASCII";
$ary[] = "EUC-CN";

$ary[] = "GB2312";//简体码

$ary[] = "BIG5";//繁体码



$ary[] = "JIS";//日文编码

$encoding=mb_detect_encoding($str, $ary);

if(empty($encoding)){

$encoding= self::detect_utf_encoding($str);

}

return $encoding;

}

}

?>