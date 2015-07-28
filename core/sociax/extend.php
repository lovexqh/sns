<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
+------------------------------------------------------------------------------
 * Think扩展函数库 需要手动加载后调用或者放入项目函数库
+------------------------------------------------------------------------------
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>
 * @version  $Id$
+------------------------------------------------------------------------------
 */

/**
 * 获取客户端IP地址
 */
function get_client_ip() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}


/**
+----------------------------------------------------------
 * 字符串截取，支持中文和其它编码
+----------------------------------------------------------
 * @static
 * @access public
+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    if($suffix && $str != $slice) return $slice."...";
    return $slice;
}
/**
+----------------------------------------------------------
 * 字符串截取，支持中文和其它编码
+----------------------------------------------------------
 * @static
 * @access public
+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function mStr($str, $length, $charset="utf-8", $suffix=true){
    return msubstr($str, 0, $length, $charset, $suffix);
}
/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
+----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    $chars   =   str_shuffle($chars);
    $str     =   substr($chars,0,$len);
    return $str;
}

/**
+----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
+----------------------------------------------------------
 * @param string $fmode 文件名
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function build_verify ($length=4,$mode=1) {
    return rand_string($length,$mode);
}

/**
+----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function byte_format($size, $dec=2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size,$dec)." ".$a[$pos];
}

/**
+----------------------------------------------------------
 * 检查字符串是否是UTF8编码
+----------------------------------------------------------
 * @param string $string 字符串
+----------------------------------------------------------
 * @return Boolean
+----------------------------------------------------------
 */
function is_utf8($string) {
    return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}
/**
+----------------------------------------------------------
 * 代码加亮
+----------------------------------------------------------
 * @param String  $str 要高亮显示的字符串 或者 文件名
 * @param Boolean $show 是否输出
+----------------------------------------------------------
 * @return String
+----------------------------------------------------------
 */
function highlight_code($str,$show=false) {
    if(file_exists($str)) {
        $str    =   file_get_contents($str);
    }
    $str  =  stripslashes(trim($str));
    // The highlight string function encodes and highlights
    // brackets so we need them to start raw
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

    // Replace any existing PHP tags to temporary markers so they don't accidentally
    // break the string out of PHP, and thus, thwart the highlighting.

    $str = str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);

    // The highlight_string function requires that the text be surrounded
    // by PHP tags.  Since we don't know if A) the submitted text has PHP tags,
    // or B) whether the PHP tags enclose the entire string, we will add our
    // own PHP tags around the string along with some markers to make replacement easier later

    $str = '<?php //tempstart'."\n".$str.'//tempend ?>'; // <?

    // All the magic happens here, baby!
    $str = highlight_string($str, TRUE);

    // Prior to PHP 5, the highlight function used icky font tags
    // so we'll replace them with span tags.
    if (abs(phpversion()) < 5)
    {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }

    // Remove our artificially added PHP
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);

    // Replace our markers back to PHP tags.
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line   =   explode("<br />", rtrim(ltrim($str,'<code>'),'</code>'));
    $result =   '<div class="code"><ol>';
    foreach($line as $key=>$val) {
        $result .=  '<li>'.$val.'</li>';
    }
    $result .=  '</ol></div>';
    $result = str_replace("\n", "", $result);
    if( $show!== false) {
        echo($result);
    }else {
        return $result;
    }
}

/**
 * 输出安全的html
 */
function h($text, $tags = null){
    $text	=	trim($text);
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤注释
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text	=	preg_replace('/<\?|\?'.'>/','',$text);
    //完全过滤js
    $text	=	preg_replace('/<script?.*\/script>/','',$text);

    $text	=	str_replace('[','&#091;',$text);
    $text	=	str_replace(']','&#093;',$text);
    $text	=	str_replace('|','&#124;',$text);
    //过滤换行符
    $text	=	preg_replace('/\r?\n/','',$text);
    //br
    $text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
    $text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
    //过滤危险的属性，如：过滤on事件lang js
    while(preg_match('/(<[^><]+) (lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    if(empty($tags)) {
        $tags = 'table|tbody|td|th|tr|i|b|u|strong|img|p|br|div|span|em|ul|ol|li|dl|dd|dt|a|alt|h[1-9]?';
        $tags.= '|object|param|embed';	// 音乐和视频
    }
    //允许的HTML标签
    $text	=	preg_replace('/<(\/?(?:'.$tags.'))( [^><\[\]]*)?>/i','[\1\2]',$text);
    //过滤多余html
    $text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|style|xml)[^><]*>/i','',$text);
    //过滤合法的html标签
    while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
    }
    //转换引号
    while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
        $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4],$text);
    }
    //过滤错误的单个引号
    // 修改:2011.05.26 kissy编辑器中表情等会包含空引号, 简单的过滤会导致错误
    //	while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
    //		$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
    //	}
    //转换其它所有不合法的 < >
    $text	=	str_replace('<','&lt;',$text);
    $text	=	str_replace('>','&gt;',$text);
    $text   =   str_replace('"','&quot;',$text);
    //$text   =   str_replace('\'','&#039;',$text);
    //反转换
    $text	=	str_replace('[','<',$text);
    $text	=	str_replace(']','>',$text);
    $text	=	str_replace('|','"',$text);
    //过滤多余空格
    $text	=	str_replace('  ',' ',$text);
    return $text;
}

/**
 * 输出纯文本
 */
function text($text,$parseBr=false){
    $text = htmlspecialchars_decode($text);
    $text	=	safe($text,'text');
    if(!$parseBr){
        $text	=	str_ireplace(array("\r","\n","\t","&nbsp;"),'',$text);
        $text	=	htmlspecialchars($text,ENT_QUOTES);
    }else{
        $text	=	htmlspecialchars($text,ENT_QUOTES);
        $text	=	nl2br($text);
    }
    $text	=	trim($text);
    return $text;
}

/**
 * 输出安全的纯代码
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $text
 * @param string $type
 * @param string $tagsMethod
 * @param string $attrMethod
 * @param number $xssAuto
 * @param unknown $tags
 * @param unknown $attr
 * @param unknown $tagsBlack
 * @param unknown $attrBlack
 * @return unknown
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:29:00
+----------------------------------------------------------
 */
function safe($text,$type='html',$tagsMethod=true,$attrMethod=true,$xssAuto = 1,$tags=array(),$attr=array(),$tagsBlack=array(),$attrBlack=array()){

    //无标签格式
    $text_tags	=	'';

    //只存在字体样式
    $font_tags	=	'<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';

    //标题摘要基本格式
    $base_tags	=	$font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';

    //兼容Form格式
    $form_tags	=	$base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';

    //内容等允许HTML的格式
    $html_tags	=	$base_tags.'<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';

    //专题等全HTML格式
    $all_tags	=	$form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';

    //过滤标签
    $text	=	strip_tags($text, ${$type.'_tags'} );

    //过滤攻击代码
    if($type!='all'){
        //过滤危险的属性，如：过滤on事件lang js
        while(preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i',$text,$mat)){
            $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
        }
        while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
            $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
        }
    }
    return $text;
}



/**
 * 转换为安全的纯文本
 *
 * @param string  $text
 * @param boolean $parse_br    是否转换换行符
 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
 * @return string|null string:被转换的字符串 null:参数错误
 */
function t($text, $parse_br = false, $quote_style = ENT_NOQUOTES)
{
    if (is_numeric($text))
        $text = (string)$text;

    if (!is_string($text))
        return null;

    if (!$parse_br) {
        $text = str_replace(array("\r","\n","\t"), ' ', $text);
    } else {
        $text = nl2br($text);
    }

    //$text = stripslashes($text);
    $text = htmlspecialchars($text, $quote_style, 'UTF-8');

    return $text;
}
/**
 +----------------------------------------------------------
 + 去除转义字符（防注入）
 +----------------------------------------------------------
 + @param	@param unknown $array 要转换的数组
 + @param	@param string $istext 是否返回为安全的纯文本
 + @param	@return Ambigous <string, NULL>
 + @return	Ambigous <string, NULL>
 + @author	小波 (Administrator)
 +----------------------------------------------------------
 + 创建时间：	2013-10-11 上午11:10:26
 +----------------------------------------------------------
 */
function stripslashes_array($array, $istext=false) {   
    if (is_array($array)) {   
        foreach ($array as $k => $v) {   
            $array[$k] = stripslashes_array($v, $istext);   
        }   
    } else if (is_string($array)) {
        //$array = stripslashes($array);
        $array = $istext ? t($array,false,ENT_QUOTES) : stripslashes($array);
        //字符串比对解析
        if($istext){
            //要过滤的特殊字符
            $dowith = array('/*','*','/--','--',';','*/','=','!',' and ',' or ',"'",'"',' ','　');
            foreach ($dowith as $key){
                $array = str_replace($key,"",$array);
            }
            if (is_numeric($array)) {
                $array = intval($array);
            }
        }
    }
    return $array;   
}
/**
 +----------------------------------------------------------
 + 去除特殊字符（防注入）当服务器端magic_quotes_gpc未开启的时候
 +----------------------------------------------------------
 + @param	@param unknown $array
 + @param	@param string $istext
 + @param	@return Ambigous <string, mixed, number>
 + @return	Ambigous <string, mixed, number>
 + @author	小波 (Administrator)
 +----------------------------------------------------------
 + 创建时间：	2013-10-11 下午4:06:57
 +----------------------------------------------------------
 */
function addslashes_array($array, $istext=false){
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            $array[$k] = addslashes_array($v, $istext);
        }
    } else if (is_string($array)) {
        $array = addslashes($array);
        //字符串比对解析
        if($istext){
            //要过滤的特殊字符
            $dowith = array('/*','*','/--','--',';','*/','=','!',' and ',' or ',"'",'"',' ','　');
            foreach ($dowith as $key){
                $array = str_replace($key,"",$array);
            }
            if (is_numeric($array)) {
                $array = intval($array);
            }
        }
    }
    return $array;
}
/**
 * 解析jsescape
 */
function unescape($str) {
    $str = rawurldecode($str);
    preg_match_all("/(?:%u.{4})|.+/",$str,$r);
    $ar = $r[0];
    foreach($ar as $k=>$v) {
        if(substr($v,0,2) == "%u" && strlen($v) == 6)
            $ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));
    }
    return join("",$ar);
}

/**
 * 解析UBB
 */
function ubb($Text) {
    $Text=trim($Text);
    $Text = str_replace('&#091;','[',$Text);
    $Text = str_replace('&#093;',']',$Text);
    // $Text=htmlspecialchars($Text);
    $Text = html_entity_decode($Text);
    // $Text=preg_replace("/\[p\](.+?)\[\/p\]/is", "<p>\\1</p>", $Text );
    // $Text=preg_replace("/\[p=(.+?)\](.+?)\[\/p\]/is", "<p class=\"\\1\">\\2</p>", $Text );
    // $Text=preg_replace("/\[span=(.+?)\](.?)\[\/span\]/is", "<span class=\"\\1\">\\2</span>", $Text );
    $Text=preg_replace("/\[face:(.+?)\]/","<img src=\"".SITE_URL.'/apps/forum/Tpl/default/Public/js/kissy/smilies/default/'."\\1.gif\" />",$Text);

    $Text=preg_replace("/\\t/is","  ",$Text);
    $Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
    $Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
    $Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
    $Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
    $Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
    $Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
    $Text=preg_replace("/\[separator\]/is","",$Text);
    $Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$Text);
    $Text=preg_replace("/\[url=http:\/\/([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
    $Text=preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
    $Text=preg_replace("/\[url\]http:\/\/([^\[]*)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\1</a>",$Text);
    $Text=preg_replace("/\[url\]([^\[]*)\[\/url\]/is","<a href=\"\\1\" target=_blank>\\1</a>",$Text);
    $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text);
    $Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
    $Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text);
    $Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
    $Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
    $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
    $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
    $Text=preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis","color_txt('\\1')",$Text);
    $Text=preg_replace("/\[emot\](.+?)\[\/emot\]/eis","emot('\\1')",$Text);
    $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
    $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
    $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
    $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is"," <div class='quote'><h5>引用:</h5><blockquote>\\1</blockquote></div>", $Text);
    $Text=preg_replace("/\[code\](.+?)\[\/code\]/eis","highlight_code('\\1')", $Text);
    $Text=preg_replace("/\[php\](.+?)\[\/php\]/eis","highlight_code('\\1')", $Text);
    $Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div class='sign'>\\1</div>", $Text);
    $Text=preg_replace("/\\n/is","<br/>",$Text);
    return $Text;
}

/**
 * 随机生成一组字符串
 */
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}

/**
 *
 */
function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

/**
+----------------------------------------------------------
 * 把返回的数据集转换成Tree
+----------------------------------------------------------
 * @access public
+----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
+----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
+----------------------------------------------------------
 * 对查询结果集进行排序
+----------------------------------------------------------
 * @access public
+----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
+----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
+----------------------------------------------------------
 * 在数据列表中搜索
+----------------------------------------------------------
 * @access public
+----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
+----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}

/**
 * 发送Http状态信息
 */
function send_http_status($status) {
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if(array_key_exists($code,$_status)) {
        header('HTTP/1.1 '.$code.' '.$_status[$code]);
    }
}

/**
 * 发送常用http header信息
 */
function send_http_header($type='utf8'){
    //utf8,html,wml,xml,图片、文档类型 等常用header
    switch($type){
        case 'utf8':
            header("Content-type: text/html; charset=utf-8");
            break;
        case 'xml':
            header("Content-type: text/xml; charset=utf-8");
            break;
    }
}

/**
 * bmp图像处理兼容函数
 */
function imagecreatefrombmp($fname) {

    $buf	=	@file_get_contents($fname);

    if(strlen($buf)<54) return false;

    $file_header=unpack("sbfType/LbfSize/sbfReserved1/sbfReserved2/LbfOffBits",substr($buf,0,14));

    if($file_header["bfType"]!=19778) return false;
    $info_header=unpack("LbiSize/lbiWidth/lbiHeight/sbiPlanes/sbiBitCountLbiCompression/LbiSizeImage/lbiXPelsPerMeter/lbiYPelsPerMeter/LbiClrUsed/LbiClrImportant",substr($buf,14,40));
    if($info_header["biBitCountLbiCompression"]==2) return false;
    $line_len=round($info_header["biWidth"]*$info_header["biBitCountLbiCompression"]/8);
    $x=$line_len%4;
    if($x>0) $line_len+=4-$x;

    $img=imagecreatetruecolor($info_header["biWidth"],$info_header["biHeight"]);
    switch($info_header["biBitCountLbiCompression"]){
        case 4:
            $colorset=unpack("L*",substr($buf,54,64));
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    if($x%2)
                        $colors[]=$colorset[(ord($buf[$y_pos+($x+1)/2])&0xf)+1];
                    else
                        $colors[]=$colorset[((ord($buf[$y_pos+$x/2+1])>>4)&0xf)+1];
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 8:
            $colorset=unpack("L*",substr($buf,54,1024));
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $colors[]=$colorset[ord($buf[$y_pos+$x])+1];
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 16:
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $i=$x*2;
                    $color=ord($buf[$y_pos+$i])|(ord($buf[$y_pos+$i+1])<<8);
                    $colors[]=imagecolorallocate($img,(($color>>10)&0x1f)*0xff/0x1f,(($color>>5)&0x1f)*0xff/0x1f,($color&0x1f)*0xff/0x1f);
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 24:
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $i=$x*3;
                    $colors[]=imagecolorallocate($img,ord($buf[$y_pos+$i+2]),ord($buf[$y_pos+$i+1]),ord($buf[$y_pos+$i]));
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        default:
            return false;
            break;
    }
    return $img;
}
/**
 * bmp图像处理兼容函数
 */
function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0) {
    if (!in_array($bit, array(1, 4, 8, 16, 24, 32)))
    {
        $bit = 8;

    }
    else if ($bit == 32) // todo:32 bit
    {
        $bit = 24;
    }

    $bits = pow(2, $bit);

    // 调整调色板
    imagetruecolortopalette($im, true, $bits);
    $width = imagesx($im);
    $height = imagesy($im);
    $colors_num = imagecolorstotal($im);

    if ($bit <= 8)
    {
        // 颜色索引
        $rgb_quad = '';
        for ($i = 0; $i < $colors_num; $i ++)
        {
            $colors = imagecolorsforindex($im, $i);
            $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";         }

        // 位图数据
        $bmp_data = '';

        // 非压缩
        if ($compression == 0 || $bit < 8)
        {
            if (!in_array($bit, array(1, 4, 8)))
            {
                $bit = 8;
            }

            $compression = 0;

            // 每行字节数必须为4的倍数，补齐。


            $extra = '';
            $padding = 4 - ceil($width / (8 / $bit)) % 4;
            if ($padding % 4 != 0)
            {
                $extra = str_repeat("\0", $padding);
            }

            for ($j = $height - 1; $j >= 0; $j --)
            {
                $i = 0;
                while ($i < $width)
                {
                    $bin = 0;
                    $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

                    for ($k = 8 - $bit; $k >= $limit; $k -= $bit)
                    {
                        $index = imagecolorat($im, $i, $j);
                        $bin |= $index << $k;
                        $i ++;
                    }

                    $bmp_data .= chr($bin);
                }

                $bmp_data .= $extra;
            }
        }
        // RLE8 压缩
        else if ($compression == 1 && $bit == 8)
        {
            for ($j = $height - 1; $j >= 0; $j --)
            {
                $last_index = "\0";
                $same_num   = 0;
                for ($i = 0; $i <= $width; $i ++)
                {
                    $index = imagecolorat($im, $i, $j);
                    if ($index !== $last_index || $same_num > 255)
                    {
                        if ($same_num != 0)
                        {
                            $bmp_data .= chr($same_num) . chr($last_index);
                        }

                        $last_index = $index;
                        $same_num = 1;
                    }
                    else
                    {
                        $same_num ++;
                    }
                }

                $bmp_data .= "\0\0";
            }

            $bmp_data .= "\0\1";
        }

        $size_quad = strlen($rgb_quad);
        $size_data = strlen($bmp_data);
    }
    else
    {
        // 每行字节数必须为4的倍数，补齐。
        $extra = '';
        $padding = 4 - ($width * ($bit / 8)) % 4;
        if ($padding % 4 != 0)
        {
            $extra = str_repeat("\0", $padding);
        }

        // 位图数据
        $bmp_data = '';

        for ($j = $height - 1; $j >= 0; $j --)
        {
            for ($i = 0; $i < $width; $i ++)
            {
                $index = imagecolorat($im, $i, $j);
                $colors = imagecolorsforindex($im, $index);

                if ($bit == 16)
                {
                    $bin = 0 << $bit;

                    $bin |= ($colors['red'] >> 3) << 10;
                    $bin |= ($colors['green'] >> 3) << 5;
                    $bin |= $colors['blue'] >> 3;

                    $bmp_data .= pack("v", $bin);
                }
                else
                {
                    $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
                }

                // todo: 32bit;
            }

            $bmp_data .= $extra;
        }

        $size_quad = 0;
        $size_data = strlen($bmp_data);
        $colors_num = 0;
    }

    // 位图文件头
    $file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);

    // 位图信息头
    $info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);
    // 写入文件
    if ($filename != '')
    {
        $fp = fopen("test.bmp", "wb");

        fwrite($fp, $file_header);
        fwrite($fp, $info_header);
        fwrite($fp, $rgb_quad);
        fwrite($fp, $bmp_data);
        fclose($fp);

        return 1;
    }

    // 浏览器输出
    header("Content-Type: image/bmp");
    echo $file_header . $info_header;
    echo $rgb_quad;
    echo $bmp_data;

    return 1;
}

/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 */
function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime		=	time();
    $dTime		=	$cTime - $sTime;
    $dDay		=	intval(date("z",$cTime)) - intval(date("z",$sTime));
    //$dDay		=	intval($dTime/3600/24);
    $dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if($type=='normal'){
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
            //今天的数据.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600)."小时前";
            return '今天'.date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }elseif($type=='mohu'){
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小时前";
        }elseif( $dDay > 0 && $dDay<=7 ){
            return intval($dDay)."天前";
        }elseif( $dDay > 7 &&  $dDay <= 30 ){
            return intval($dDay/7) . '周前';
        }elseif( $dDay > 30 ){
            return intval($dDay/30) . '个月前';
        }
        //full: Y-m-d , H:i:s
    }elseif($type=='full'){
        return date("Y-m-d , H:i:s",$sTime);
    }elseif($type=='ymd'){
        return date("Y-m-d",$sTime);
    }elseif($type=='cv'){
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小时前";
        }elseif($dYear==0){
            return date("m月d日",$sTime);
        }elseif($dYear>0){
            return date("Y年m月d日",$sTime);
        }else{
            return date("m月d日",$sTime);
        }
    }else{
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分钟前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小时前";
        }elseif($dYear==0){
            return date("Y-m-d H:i:s",$sTime);
        }else{
            return date("Y-m-d H:i:s",$sTime);
        }
    }
}

/**
 * 时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $format 时间格式
 * @return string
 */
function dateFormat($sTime, $format = null) {
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime		=	time();
    $dTime		=	$cTime - $sTime;

    if($dTime > 0 && $dTime < 60 ){
        return $dTime . ' ' . L('seconds_ago');
    } else if($dTime > 0 && $dTime < 3600 ){
        return intval($dTime/60) . ' ' . L('minutes_ago');
    } else {
        if (!$format) {
            $time_format = array(
                'en'    => 'M d,Y H:i',
                'zh-cn' => 'Y-m-d H:i'
            );
            $format = $time_format[LANG_SET];
            unset($time_format);
        } else {
            if ('Ymd' == $format) {
                $time_format = array(
                    'en'    => 'M d,Y',
                    'zh-cn' => 'Y-m-d'
                );
            }
            $format = $time_format[LANG_SET];
            unset($time_format);
        }
        return date($format, $sTime);
    }
}


/***
 * 格式化时间戳
 */
function setDate($format = null ,$sTime){
    //sTime=源时间
    $cTime = 60*60*8;
    return date($format, $sTime+$cTime);
}

/**
 * 获取当前登录用户的UID
 */
function getMid(){
    return intval($_SESSION['mid']);
}

/**
 +----------------------------------------------------------
 * 获取Ucenter中的uid
 +----------------------------------------------------------
 * @param $mid 要查的人在社区中的uid 如果为空则返回当前人物的ucuid
 * @return number
 * @author 小波 2013-7-2
 +----------------------------------------------------------
 */
function getUcUid($mid=''){
	$uid = 0;
	if (empty($mid)) {
		$uid = intval($_SESSION['ucInfo']['uid']);
	}
	if (!$uid) {
		if (empty($mid)) {
			global $ts;
			$mid = $ts['user']['uid'];
		}
		$uid = M('ucenter_user_link')->where("uid='{$mid}'")->getField('uc_uid');
	}
	return $uid;
}

/**
 * 获取用户姓名
 */
function getUserName($uid,$lang='zh') {
    global $ts;
    if ($uid == $ts['user']['uid'] || $uid == $ts['user']['uname'])
        return $ts['user']['uname'];

    static $_MapName = array();
    if(!isset($_MapName[$uid])){
        if(is_numeric($uid)){
            $userinfo = D('User', 'home')->getUserByIdentifier($uid, 'uid');
        }else{
            $userinfo = D('User', 'home')->getUserByIdentifier($uid, 'uname');
        }
        $_MapName[$uid] = $userinfo['uname'];
    }
    return htmlspecialchars($_MapName[$uid]);
}

/**
 * 获取用户Gid[Mentor项目]
 *
 */
function getUserAtString($uid, $type = 'uid')
{
    if ($uid == $GLOBALS['ts']['user']['uid'])
        return $GLOBALS['ts']['user']['uname'];

    static $_MapName = array();
    if(!isset($_MapName[$uid])){
        $userinfo = D('User', 'home')->getUserByIdentifier($uid, $type);
        $_MapName[$uid] = $userinfo['uname'];
    }
    return $_MapName[$uid];
}

/**
 * 返回解析的空间地址
 */
function getUserSpace($uid,$class,$target,$text, $icon = true) {
    static $_userinfo = array();
    if (!isset($_userinfo[$uid])) {
        $_userinfo[$uid] = D('User', 'home')->getUserByIdentifier($uid, 'uid');
    }
    $target = ($target) ? $target : '_self';
    $text   = ($text)   ? $text   : $_userinfo[$uid]['uname'];

    preg_match('|{(.*?)}|isU',$text,$t);

    if($t){
        if ($t['1']=='uname') {
            $text = str_replace("{uname}", $_userinfo[$uid]['uname'], $text);
            $class  = ($class)  ? $class  : 'username';
        } else {
            $face = preg_replace("/{uavatar}|{uavatar\\=(.*?)}/e",  "getUserFace(\$uid, '\\1')", $text);
            $text = "<img src=".$face.">";
            $class  = ($class)  ? $class  : 'userface';
            $icon = false;
        }
    }
    if ($icon) {
        $user_name_end = null;
        Addons::hook('user_name_end', array('uid'=>$uid, 'html'=>& $user_name_end));
    }

    if ($_userinfo[$uid]['domain'])
        $url = U('home/Space/index', array('uid' => $_userinfo[$uid]['domain']));
    else
        $url = U('home/Space/index', array('uid' => $uid));

    $user_space_info = "<a href='{$url}' class='{$class}' target='{$target}' title='{$_userinfo[$uid]['uname']}的个人主页'>$text" . $user_name_end . "</a>";
    Addons::hook('get_user_space_output', array('uid'=>$uid, 'space_info'=>&$user_space_info));
    return $user_space_info;
}

/**
+----------------------------------------------------------
 * 获取用户详细信息
+----------------------------------------------------------
 * @param intval $uid 用户ID
 * @param string $uname 用户昵称
 * @param intval $mid 当前登录的用户ID
 * @param string $status
 * @return boolean|multitype:number unknown string NULL Ambigous <boolean, string, mixed>
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-16 下午3:55:59
+----------------------------------------------------------
 */
function getUserInfo($uid, $uname, $mid, $status = false)
{
    $data = array();
    $uid  = intval($uid);
    $user = null;
    if ($uid > 0) {
        $user = D('User', 'home')->getUserByIdentifier($uid, 'uid');
    } else if (!empty($uname)) {
        $user = D('User', 'home')->getUserByIdentifier($uname, 'uname');
    }
    if (!$user)
        return false;
    if($uid != $mid){
        $isBlack = D('UserPrivacy','home')->isInBlackList($uid,$mid);
        $data['isInBlackList'] = $isBlack?1:0;
    }
    $data['uid']       = $user['uid'];
    $data['uname']     = $user['uname'];
    //真实姓名
    $data['realname']     = $user['realname'];
    $data['province']  = $user['province'];
    $data['city']      = $user['city'];
    $data['location']  = $user['location'];
    $data['face']      = getUserFace($user['uid']);
    $data['space']     = ($user['domain'])?U('home/Space/index',array('uid'=>$user['domain'])):U('home/Space/index',array('uid'=>$user['uid']));
    $data['sex']       = getSex($user['sex']);
    $data['weibo_count']     = model('UserCount')->getUserWeiboCount($user['uid']);
    $data['favorite_count']  = (int) M('weibo_favorite')->where('uid='.$user['uid'])->count();
    $data['followers_count'] = model('UserCount')->getUserFollowerCount($user['uid']);
    $data['followed_count']  = model('UserCount')->getUserFollowingCount($user['uid']);
    $data['is_followed']     = getFollowState($mid,$user['uid']);
    $data['is_verified']	 = intval(M('user_verified')->getField('verified', "uid={$uid}"));
    if ($status) {
        $status = M('weibo')->where('uid='.$user['uid'])->order('weibo_id DESC')->find();
        $data['status'] = ($status) ? D('Weibo','weibo')->getOneApi('', $status) : '';
    }
    return $data;
}

/**
 * 获取关注状态
 *
 * @param int $uid 用户ID
 * @param int $fid 好友ID
 * @param int $type 0:关注好友 1:关注话题
 * @return string eachfollow:相互关注 | havefollow:已关注 | unfollow:未关注 | 空:同一个用户
 *
 * TODO: 使用缓存
 */
function getFollowState($uid,$fid,$type=0) {

    if ($uid <= 0 || $fid <= 0)
        return 'unfollow';

    if ($type == 0) {
        if ($uid==$fid)
            return 'unfollow';

        if (M('weibo_follow')->where("(uid=$uid AND fid=$fid AND type=0) OR (uid=$fid AND fid=$uid AND type=0)")->count() == 2) {
            return 'eachfollow';
        }else if ( M('weibo_follow')->where("uid=$uid AND fid=$fid AND type=0")->count()) {
            return 'havefollow';
        }else {
            return 'unfollow';
        }
    }else if($type == 1) {
        $db_prefix  = C('DB_PREFIX');
        if(is_numeric($fid)){
            $fid['topic_id'] = $fid;
        }
        $map = $fid['topic_id']?"AND a.topic_id={$fid['topic_id']}":"AND a.name='{$fid['name']}'";
        $topic = M('weibo_follow')->field('a.topic_id')
            ->table("{$db_prefix}weibo_topic a LEFT JOIN {$db_prefix}weibo_follow b ON b.fid=a.topic_id")
            ->where("b.uid={$uid} AND b.type=1 {$map}")
            ->find();
        if($topic){
            return 'havefollow';
        }else{
            return 'unfollow';
        }
    }
}

/**
 * 检查给定用户是否收藏给定微广播
 *
 * @param int 		 $weibo_id 		 微广播ID
 * @param int 		 $uid      		 用户ID
 * @param array|null $weibo_id_array $weibo_id所属的微广播集合(不为空时会一次性查询, 以减少数据库请求数)
 * @param string     $key            为防止前一次调用对后一次调用的干扰, 为每个$weibo_id_array赋予唯一key
 * @return int 已收藏返回1, 否则返回0
 */
function isfavorited($weibo_id, $uid, $weibo_id_array = null, $key = '')
{
    if (is_array($weibo_id_array) && !empty($weibo_id_array)) {
        static $_favorited = array();
        $key = !empty($key) ? $key : md5(serialize($weibo_id_array)); // 为防止前一次调用对后一次调用的干扰, 为每个$weibo_id_array赋予唯一key

        if (!isset($_favorited[$key])) {
            $map['uid']      = $uid;
            $map['weibo_id'] = array('in', $weibo_id_array);
            $res = M('weibo_favorite')->where($map)->field('weibo_id')->limit(count($weibo_id_array))->findAll();
            $_favorited[$key] = empty($res) ? array() : getSubByKey($res, 'weibo_id');
        }
        return in_array($weibo_id, $_favorited[$key]);
    } else {
        return M('weibo_favorite')->where("weibo_id=$weibo_id AND uid=$uid")->find() ? '1' : '0';
    }
}

/**
 * 是否为黑名单成员
 *
 * @param int $uid 用户ID
 * @param int $fid 好友ID
 * @return int
 */
function isBlackList($uid, $fid) {
    $key = $uid . '_' . $fid;
    static $_black = array();
    if (!isset($_black[$key]))
        $_black[$key] = M('user_blacklist')->where("uid=$uid AND fid=$fid")->find() ? '1' : '0';

    return $_black[$key];
}

/**
 * 获取用户头像
 *
 * @param int    $uid  用户ID
 * @param string $size 头像大小 m:中图 | s:小图 | 其它:大图
 * @return string 头像的URL地址
 */
/*function getUserFace($uid,$size){
 $size = ($size)?$size:'m';
 if($size=='m'){
 $type = 'middle';
 }elseif ($size=='s'){
 $type = 'small';
 }else{
 $type = 'big';
 }
 $userface = SITE_PATH.'/data/uploads/avatar/'.$uid.'/'.$type.'.jpg';
 if(is_file($userface)){
 return SITE_URL.'/data/uploads/avatar/'.$uid.'/'.$type.'.jpg';
 }else{
 return THEME_URL."/images/user_pic_$type.gif";
 }
 }*/
function getUserFace($uid,$size){
    $size = ($size)?$size:'m';
    if($size=='m'){
        $type = 'middle';
    }elseif ($size=='s'){
        $type = 'small';
    }else{
        $type = 'big';
    }
    $uid_to_path = convertUidToPath($uid);
    $userface = SITE_PATH.'/data/uploads/avatar' . $uid_to_path . '/' . $type. '.jpg';
    if(is_file($userface)){
        return SITE_URL.'/data/uploads/avatar' . $uid_to_path . '/' . $type . '.jpg';
    }else{
    	
    	$uc_uid = M('ucenter_user_link')->where('uid = '.$uid)->getField('uc_uid');
    	$userinfo = get_baseinfo_by_uid($uc_uid);
    	//$usertype = 't';
    	if($userinfo['identitytype'] == '3'){
    		$usertype = 'hs';
    	}else if($userinfo['identitytype'] == '2'){
    		$usertype = 't';
    	}else{
            $usertype = 'hs';
        }
        if($userinfo['xbm'] == 1){
        	$sex = 'm';
        }else if($userinfo['xbm']!=null && $userinfo['xbm'] == 2){
        	$sex = 'w';
        }else{
        	$usersex = M('user')->where('uid='.$uid)->getField('sex');
        	if($usersex=='1'){
        		$sex = 'm';
        	}else if($usersex!=null && ($usersex=='0' || $usersex=='2')){
        		$sex = 'w';
        	}else{
        		$sex = '';
        	}
        	
        }
        //return THEME_URL."/images/user_pic_{$type}.gif";
    	return THEME_URL."/images/user_".$usertype.$sex."_{$type}.jpg";
    }
}

/**
+----------------------------------------------------------
 * 根据社区用户ID获取用户的身份
+----------------------------------------------------------
 * @param intval $uid 社区用户ID
 * @return string 用户身份
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:41:31
+----------------------------------------------------------
 */
function getUserIdentity($uid){
    $uid = intval($uid);
    if(UC_SYNC){
        $result = M('ucenter_user_link')->field('uc_uid')->where('uid='.$uid)->find();
        $identity = uc_get_identity_by_uid($result['uc_uid']);
        $identval = "";
        switch ($identity){
            case "1":
                $identval = "管理员";
                break;
            case "2":
                $identval = "老师";
                break;
            case "3":
                $identval = "学生";
                break;
            case "4":
                $identval = "家长";
                break;
            default:
        }
        return $identval;
    }else{
        return "会员";
    }
}

/**
+----------------------------------------------------------
 * 根据社区用户ID获取用户所在学校名称
+----------------------------------------------------------
 * @param intval $uid 用户ID
 * @param intval $islink 是否输出为链接
 * @return string 用户所在学校名称
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:55:58
+----------------------------------------------------------
 */
function getUserSchool($uid,$islink = 0){
    $uid = intval($uid);
    if(UC_SYNC){
        $result = M('ucenter_user_link')->field('uc_uid')->where('uid='.$uid)->find();
        $school = uc_get_school_by_uid($result['uc_uid']);
        if($islink){
            return "<a href='".U('space/School/index',array('id'=>$school['id']))."' target='_blank'>".$school['xxmc']."</a>";
        }else{
            return $school['xxmc'];
        }
    }
}

/**
+----------------------------------------------------------
 * 根据社区用户ID获取用户所在部门名称
+----------------------------------------------------------
 * @param intval $uid 用户ID
 * @param intval $islink 是否输出为链接
 * @return string 用户所在部门名称
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:55:58
+----------------------------------------------------------
 */
function getUserDepartment($uid,$islink = 0){
    $uid = intval($uid);
    if(UC_SYNC){
        $user = M('ucenter_user_link')->field('uc_uid')->where('uid='.$uid)->find();
        $result = uc_get_department_by_uid($user['uc_uid']);
        if($islink){
            return "<a href='".U('space/Class/index',array('id'=>$result['id']))."' target='_blank'>".$result['departname']."</a>";
        }else{
            return $result['departname'];
        }
    }
}
/**
+----------------------------------------------------------
 * 根据用户ID获取用户真实姓名
+----------------------------------------------------------
 * @param number $uid 用户ID
 * @return string
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-18 上午11:59:16
+----------------------------------------------------------
 */
function getRealName($uid=0){
    $uid = intval($uid);
    if ($uid) {
        $user = M('user')->field('realname')->where('uid='.$uid)->find();
    }
    return $user['realname'];
}

/**
 * 将用户ID转换为三级路径
 */
function convertUidToPath($uid){
    return '/' . $uid;
    //$md5 = md5($uid);
    //return '/' . substr($md5, 0, 2) . '/' . substr($md5, 2, 2) . '/' . substr($md5, 4, 2);
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $uid
 * @return boolean
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:33:34
+----------------------------------------------------------
 */
function hasUserFace($uid){
    return getUserFace($uid, 'm') != THEME_URL."/images/user_pic_middle.gif";
}

/**
 * 获取给定用户的用户组图标
 */
function getUserGroupIcon($uid){
    static $_var = array();
    if (!isset($_var[$uid]))
        $_var[$uid] = model('UserGroup')->getUserGroupIcon($uid);

    return $_var[$uid];
}

/**
 * 2012 -10 -10 添加方法
 */
function getUserGroup($uid){
    $group = model('UserGroup')->getUserGroupId($uid);
    $gid = array();
    $list = M('user_group_link')->where("uid=$uid and user_group_id<>1")->field('user_group_id')->findAll();
    foreach($list as $v){
        $gid = $v['user_group_id'];
    }
    return $gid;
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $origin
 * @param unknown $key
 * @return void|Ambigous <multitype:, unknown>
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:34:15
+----------------------------------------------------------
 */
function getSubBeKeyArray($origin, $key) {
    if (empty($origin))
        return ;

    $key = is_array($key) ? $key : explode(',', $key);
    $key = array_map('trim', $key);
    $res = array();
    foreach ($origin as $v1)
        foreach ($key as $v2)
            if (isset($v1[$v2]))
                $res[$v2][] = $v1[$v2];

    return $res;
}

/**
 * 去一个二维数组中的每个数组的固定的键知道的值来形成一个新的一维数组
 * @param $pArray 一个二维数组
 * @param $pKey 数组的键的名称
 * @return 返回新的一维数组
 */
function getSubByKey($pArray, $pKey="", $pCondition=""){
    $result = array();
    foreach($pArray as $temp_array){
        if(is_object($temp_array)){
            $temp_array = (array) $temp_array;
        }
        if((""!=$pCondition && $temp_array[$pCondition[0]]==$pCondition[1]) || ""==$pCondition) {
            $result[] = (""==$pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
        }
    }
    return $result;
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $pArray
 * @param string $pKey
 * @return unknown
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:34:31
+----------------------------------------------------------
 */
function getMultiArraySubByKey($pArray,$pKey=""){
    $result = array();
    $result = getSubByKey($temp_array,$pKey);
    foreach($pArray as $temp_array){
        if(is_object($temp_array)){
            $temp_array = (array) $temp_array;
        }
        foreach ( $temp_array as $value){
            if(is_array($value)){
                $result = array_merge(getSubByKey($value,$pKey),$result);
            }
        }
    }
    return $result;
}

/**
 * 将两个二维数组根据指定的字段来连接起来，连接的方式类似sql查询中的连接
 * @param $pArray1 一个二维数组
 * @param $pArray2 一个二维数组
 * @param $pFields 作为连接依据的字段
 * @param $pType 连接的方式，默认为左联，即在右面的数组中没有找到匹配的则对应左面的行，否则不对应
 * @return 连接好的数组
 */
function arrayJoin($pArray1, $pArray2, $pFields, $pType="left"){
    $result = array();
    foreach($pArray1 as $row1)
    {
        $is_join = false;
        foreach($pArray2 as $row2)
        {
            if(canJoin($row1, $row2, $pFields))
            {
                $result[] = array_merge($row2, $row1);
                $is_join = true;
                break;
            }
        }

        //如果是左连接并且没有找到匹配的连接
        if($is_join==false && $pType=="left")
        {
            $result[] = $row1;
        }
    }
    return $result;
}

/**
 * 判断两个行是否满足连接条件
 * @author 常伟
 * @createTime 2004-07-15
 * @param $pRow1 数组的一行
 * @param $pRow2 数组的一行
 * @param $pFields 作为连接依据的字段
 * @return 是否可以连接
 */
function canJoin($pRow1, $pRow2, $pFields)
{
    $field_array = explode(",", $pFields);
    foreach($field_array as $key)
    {
        if(strtolower($pRow1[$key])!=strtolower($pRow2[$key]))
            return false;
    }
    return true;
}


/**
 * 根据指定的键对数组排序
 *
 * 用法：
 * @code php
 * $rows = array(
 *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
 *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
 *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
 *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
 *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
 *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
 * );
 *
 * $rows = Helper_Array::sortByCol($rows, 'id', SORT_DESC);
 * dump($rows);
 * // 输出结果为：
 * // array(
 * //   array('id' => 6, 'value' => '6-1', 'parent' => 3),
 * //   array('id' => 5, 'value' => '5-1', 'parent' => 2),
 * //   array('id' => 4, 'value' => '4-1', 'parent' => 2),
 * //   array('id' => 3, 'value' => '3-1', 'parent' => 1),
 * //   array('id' => 2, 'value' => '2-1', 'parent' => 1),
 * //   array('id' => 1, 'value' => '1-1', 'parent' => 1),
 * // )
 * @endcode
 *
 * @param array $array 要排序的数组
 * @param string $keyname 排序的键
 * @param int $dir 排序方向
 *
 * @return array 排序后的数组
 */
function sortByCol($array, $keyname, $dir = SORT_ASC)
{
    return sortByMultiCols($array, array($keyname => $dir));
}

/**
 * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
 *
 * 用法：
 * @code php
 * $rows = Helper_Array::sortByMultiCols($rows, array(
 *     'parent' => SORT_ASC,
 *     'name' => SORT_DESC,
 * ));
 * @endcode
 *
 * @param array $rowset 要排序的数组
 * @param array $args 排序的键
 *
 * @return array 排序后的数组
 */
function sortByMultiCols($rowset, $args)
{
    $sortArray = array();
    $sortRule = '';
    foreach ($args as $sortField => $sortDir)
    {
        foreach ($rowset as $offset => $row)
        {
            $sortArray[$sortField][$offset] = $row[$sortField];
        }
        $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
    }
    if (empty($sortArray) || empty($sortRule)) { return $rowset; }
    eval('array_multisort(' . $sortRule . '$rowset);');
    return $rowset;
}


/**
 * 获取给定用户的Email
 *
 * @param int $uid
 */
function getUserEmail($uid) {
    $map ['uid'] = $uid;
    return M( 'User' )->where ( $map )->getField ( 'email' );
}

/**
 * 根据sexid获取性别
 *
 * @param int $sexid
 */
function getSex($sexid) {
	if($sexid==-1){
		return "未知";
	}else{
	    return ($sexid == '1') ? "男" : "女";
	}
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param string $content
 * @return unknown|boolean
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:34:59
+----------------------------------------------------------
 */
function matchImages($content = '') {
    $src = array ();
    preg_match_all ( '/<img.*src=(.*)[>|\\s]/iU', $content, $src );
    if (count ( $src [1] ) > 0) {
        foreach ( $src [1] as $v ) {
            $images [] = trim ( $v, "\"'" ); //删除首尾的引号 ' "
        }
        return $images;
    } else {
        return false;
    }
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param string $content
 * @return unknown
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:35:05
+----------------------------------------------------------
 */
function matchReplaceImages($content = ''){
    $image = preg_replace_callback('/<img.*src=(.*)[>|\\s]/iU',"matchReplaceImagesOnce",$content);
    return $image;
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $matches
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:35:09
+----------------------------------------------------------
 */
function matchReplaceImagesOnce($matches){
    $matches[1] = str_replace('"','',$matches[1]);
    return sprintf("<a class='thickbox'  href='%s'>%s</a>",$matches[1],$matches[0]);
}

/**
 * 获取字符串的长度
 *
 * 计算时, 汉字或全角字符占1个长度, 英文字符占0.5个长度
 *
 * @param string  $str
 * @param boolean $filter 是否过滤html标签
 * @return int 字符串的长度
 */
function get_str_length($str, $filter = false)
{
    if ($filter) {
        $str = html_entity_decode($str, ENT_QUOTES);
        $str = strip_tags($str);
    }
    return (strlen($str) + mb_strlen($str, 'UTF8')) / 4;
}

/**
 *
+----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
+----------------------------------------------------------
 * @param unknown $str
 * @param number $length
 * @param string $ext
 * @return unknown
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-4-23 下午10:35:15
+----------------------------------------------------------
 */
function getShort($str, $length = 40, $ext = '') {
    $str	=	htmlspecialchars($str);
    $str	=	strip_tags($str);
    $str	=	htmlspecialchars_decode($str);
    $strlenth	=	0;
	$output		=	'';
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
    foreach($match[0] as $v){
        preg_match("/[\xe0-\xef][\x80-\xbf]{2}/",$v, $matchs);
        if(!empty($matchs[0])){
            $strlenth	+=	1;
        }elseif(is_numeric($v)){
            //$strlenth	+=	0.545;  // 字符像素宽度比例 汉字为1
            $strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
        }else{
            //$strlenth	+=	0.475;  // 字符像素宽度比例 汉字为1
            $strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
        }
        if ($strlenth > $length) {
            $output .= $ext;
            break;
        }

        $output	.=	$v;
    }
    return $output;
}

/**
 * 动态通知的评论两边的引号是否显示
 */
function infoCss($info){
    if(empty($info)) return '';
    else             return "<div class='ico_cite C_C'>$info<span class='ico_cite2'></span></div>";
}

/**
 * 加密函数
 */
function jiami($txt, $key = null) {
    if (empty ( $key ))
        $key = C ( 'SECURE_CODE' );
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $nh = rand ( 0, 64 );
    $ch = $chars [$nh];
    $mdKey = md5 ( $key . $ch );
    $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
    $txt = base64_encode ( $txt );
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for($i = 0; $i < strlen ( $txt ); $i ++) {
        $k = $k == strlen ( $mdKey ) ? 0 : $k;
        $j = ($nh + strpos ( $chars, $txt [$i] ) + ord ( $mdKey [$k ++] )) % 64;
        $tmp .= $chars [$j];
    }
    return $ch . $tmp;
}

/**
 * 解密函数
 */
function jiemi($txt, $key = null) {
    if (empty ( $key ))
        $key = C ( 'SECURE_CODE' );
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $ch = $txt [0];
    $nh = strpos ( $chars, $ch );
    $mdKey = md5 ( $key . $ch );
    $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
    $txt = substr ( $txt, 1 );
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for($i = 0; $i < strlen ( $txt ); $i ++) {
        $k = $k == strlen ( $mdKey ) ? 0 : $k;
        $j = strpos ( $chars, $txt [$i] ) - $nh - ord ( $mdKey [$k ++] );
        while ( $j < 0 )
            $j += 64;
        $tmp .= $chars [$j];
    }
    return base64_decode ( $tmp );
}

/**
 * Format a mySQL string correctly for safe mySQL insert (no mater if magic quotes are on or not)
 */
function escape($str) {
    return mysql_escape_string(stripslashes($str));
}

/**
 * 获取给定IP的物理地址
 *
 * @param string $ip
 * @return string
 */
function convert_ip($ip) {
    $return = '';
    if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
        $iparray = explode('.', $ip);
        if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
            $return = '- LAN';
        } elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
            $return = '- Invalid IP Address';
        } else {
            $tinyipfile = ADDON_PATH . '/libs/misc/tinyipdata.dat';
            $fullipfile = ADDON_PATH . '/libs/misc/wry.dat';
            if(@file_exists($tinyipfile)) {
                $return = convert_ip_tiny($ip, $tinyipfile);
            } elseif(@file_exists($fullipfile)) {
                $return = convert_ip_full($ip, $fullipfile);
            }
        }
    }
    $return = iconv('GBK', 'UTF-8', $return);
    return $return;
}

/**
 * @see convert_ip()
 */
function convert_ip_tiny($ip, $ipdatafile) {
    static $fp = NULL, $offset = array(), $index = NULL;

    $ipdot = explode('.', $ip);
    $ip    = pack('N', ip2long($ip));

    $ipdot[0] = (int)$ipdot[0];
    $ipdot[1] = (int)$ipdot[1];

    if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
        $offset = unpack('Nlen', fread($fp, 4));
        $index  = fread($fp, $offset['len'] - 4);
    } elseif($fp == FALSE) {
        return  '- Invalid IP data file';
    }

    $length = $offset['len'] - 1028;
    $start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
            $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = unpack('Clen', $index{$start + 7});
            break;
        }
    }

    fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    if($index_length['len']) {
        return '- '.fread($fp, $index_length['len']);
    } else {
        return '- Unknown';
    }

}

/**
 * @see convert_ip()
 */
function convert_ip_full($ip, $ipdatafile) {
    if (! $fd = @fopen ( $ipdatafile, 'rb' )) {
        return '- Invalid IP data file';
    }

    $ip = explode ( '.', $ip );
    $ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];

    if (! ($DataBegin = fread ( $fd, 4 )) || ! ($DataEnd = fread ( $fd, 4 )))
        return;
    @$ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
    if ($ipbegin < 0)
        $ipbegin += pow ( 2, 32 );
    @$ipend = implode ( '', unpack ( 'L', $DataEnd ) );
    if ($ipend < 0)
        $ipend += pow ( 2, 32 );
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

    $BeginNum = $ip2num = $ip1num = 0;
    $ipAddr1 = $ipAddr2 = '';
    $EndNum = $ipAllNum;

    while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
        $Middle = intval ( ($EndNum + $BeginNum) / 2 );

        fseek ( $fd, $ipbegin + 7 * $Middle );
        $ipData1 = fread ( $fd, 4 );
        if (strlen ( $ipData1 ) < 4) {
            fclose ( $fd );
            return '- System Error';
        }
        $ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
        if ($ip1num < 0)
            $ip1num += pow ( 2, 32 );

        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }

        $DataSeek = fread ( $fd, 3 );
        if (strlen ( $DataSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
        fseek ( $fd, $DataSeek );
        $ipData2 = fread ( $fd, 4 );
        if (strlen ( $ipData2 ) < 4) {
            fclose ( $fd );
            return '- System Error';
        }
        $ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
        if ($ip2num < 0)
            $ip2num += pow ( 2, 32 );

        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose ( $fd );
                return '- Unknown';
            }
            $BeginNum = $Middle;
        }
    }

    $ipFlag = fread ( $fd, 1 );
    if ($ipFlag == chr ( 1 )) {
        $ipSeek = fread ( $fd, 3 );
        if (strlen ( $ipSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
        fseek ( $fd, $ipSeek );
        $ipFlag = fread ( $fd, 1 );
    }

    if ($ipFlag == chr ( 2 )) {
        $AddrSeek = fread ( $fd, 3 );
        if (strlen ( $AddrSeek ) < 3) {
            fclose ( $fd );
            return '- System Error';
        }
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return '- System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }

        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr2 .= $char;

        $AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
        fseek ( $fd, $AddrSeek );

        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;
    } else {
        fseek ( $fd, - 1, SEEK_CUR );
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;

        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return '- System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr2 .= $char;
    }
    fclose ( $fd );

    if (preg_match ( '/http/i', $ipAddr2 )) {
        $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace ( '/CZ88\.NET/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/^\s*/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/\s*$/is', '', $ipaddr );
    if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
        $ipaddr = '- Unknown';
    }

    return '- ' . $ipaddr;

}

/**
 * DES加密函数
 *
 * @param string $input
 * @param string $key
 */
function desencrypt($input,$key) {
    $size = mcrypt_get_block_size('des', 'ecb');
    $input = pkcs5_pad($input, $size);

    $td = mcrypt_module_open('des', '', 'ecb', '');
    $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    @mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
}

/**
 * DES解密函数
 *
 * @param string $input
 * @param string $key
 */
function desdecrypt($encrypted,$key) {
    $encrypted = base64_decode($encrypted);
    $td = mcrypt_module_open('des','','ecb','');	//使用MCRYPT_DES算法,cbc模式
    $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    @mcrypt_generic_init($td, $key, $iv);			//初始处理

    $decrypted = mdecrypt_generic($td, $encrypted); //解密

    mcrypt_generic_deinit($td);       				//结束
    mcrypt_module_close($td);

    $y = pkcs5_unpad($decrypted);
    return $y;
}

/**
 * @see desencrypt()
 */
function pkcs5_pad($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}

/**
 * @see desdecrypt()
 */
function pkcs5_unpad($text) {
    $pad = ord($text{strlen($text)-1});

    if ($pad > strlen($text))
        return false;
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
        return false;

    return substr($text, 0, -1 * $pad);
}

/**
 * 检查Email地址是否合法
 *
 * @return boolean
 */
function isValidEmail($email) {
    return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
}

/**
 * 检查Email是否可用
 *
 * @return boolean
 */
function isEmailAvailable($email,$uid=false) {
    return D('User', 'home')->isEmailAvailable($email,$uid);
}

/**
 * 获取给定字符串中被@用户的uid数组
 *
 * @param string $content 待搜索的内容
 * @return array 用户ID的数组
 */
function getUids($content) {
    preg_match_all("/@(.+?)([\s|:]|$)/is", $content, $matches);
    $unames = $matches[1];

    $map['uname'] = array('IN',$unames);
    $ulist = M('user')->where($map)->field('uid')->findall();
    return getSubByKey($ulist,'uid');
}

/**
 * 关键字过滤
 */
function keyWordFilter( $content ){
    $audit = model('Xdata')->lget('audit');
    if($audit['open'] && $audit['keywords']){
        $replace = $audit['replace']?$audit['replace']:'[和*谐]';
        $arr_keyword = explode('|', $audit['keywords']);
        foreach ( $arr_keyword as $k=>$v ){
            $content = str_replace($v, $replace, $content);
        }
        return $content;
    }else{
        return $content;
    }
}

/**
 * 检测内容是否含有关键字
 */
function checkKeyWord( $content ){
    $audit = model('Xdata')->lget('audit');
    if($audit['open'] && $audit['keywords']){
        $arr_keyword = explode('|', $audit['keywords']);
        foreach ( $arr_keyword as $k=>$v ){
            $num = stristr($content,$v)?$num+1:$num;
        }
        if($num){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
/**
 * 格式化微广播,替换表情/@用户/话题
 *
 * @param string  $content 待格式化的内容
 * @param boolean $url     是否替换URL
 * @return string
 */
function format($content,$url=false){
    if($url){
        $content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/u', '<a href="\1" target="_blank">\1</a>\2', $content);
    }
    $content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is",replaceEmot,$content);
    $content = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is",themeformat,$content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u",getUserId,$content);
    $content = keyWordFilter($content);
    return $content;
}

/**
 * 格式化社团微广播,替换表情/@用户/话题
 *
 * @param string  $content 待格式化的内容
 * @param boolean $url     是否替换URL
 * @return string
 */
function group_weibo_format($content, $gid, $url=false){
    $_SESSION['_group_weibo_format'] = $gid;

    if($url){
        $content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/u', '<a href="\1" target="_blank">\1</a>\2', $content);
    }
    $content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is", replaceEmot, $content);
    $content = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is", group_themeformat, $content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", getUserId, $content);
    $content = keyWordFilter($content);
    return $content;
}

/**
 * 社团话题替换 [格式化社团微广播专用]
 *
 * @param array $data
 * @see format()
 */
function group_themeformat($data){
    $res = "<a href=".U('group/Group/search',array('gid'=>$_SESSION['_group_weibo_format'],'k'=>urlencode($data[1])))." type='_blank'>".$data[0]."</a>";
    unset($_SESSION['_group_weibo_format']);
    return $res;
}

/**
 * 格式化评论, 替换表情和@用户
 *
 * @param string  $content 待格式化的内容
 * @param boolean $url     是否替换URL
 * @return string
 */
function formatComment($content,$url=false){
    if($url){
        $content = preg_replace('/[^((h|H)(r|R)(e|E)(f|F)(\s*)=(\s*)("|\')(\s*))]((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/u', '<a href="\1" target="_blank">\1</a>\2', $content);
    }
    $content = preg_replace_callback("/(\[.+?\])/is",replaceEmot,$content);
    $content = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u",getUserId,$content);
    $content = keyWordFilter( $content );
    return $content;
}

/**
 * 格式化内容, 替换URL
 *
 * @param string  $content 待格式化的内容
 * @return string
 */
function formatUrl($content,$txt=''){
    // $content = strip_tags($content);
    $finda = "</a>";
    if( strpos($content, $finda) === false){
        $contents = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/u', '<a href="\1" target="_blank">\1</a>\2', $content);
    }else{
        $contents = $content;
    }
    if ($txt!='') {
        $contents = preg_replace('/<a(.*?)>(.*?)<\/a>/i',' <a$1>'.$txt.'</a> ',$contents);
    }
    return $contents;
}

/**
 * 话题替换 [格式化微广播专用]
 *
 * @param array $data
 * @see format()
 */
function themeformat($data){
    return "<a href=".U('home/user/topics',array('k'=>urlencode($data[1])))." type='_blank'>".$data[0]."</a>";
}
/**
 * 表情替换 [格式化微广播与格式化评论专用]
 *
 * @param array $data
 * @see format()
 * @see formatComment()
 */
function replaceEmot($data) {
    if(preg_match("/#.+#/i",$data[0])) {
        return $data[0];
    }

    $info = model('Expression')->getExpressionDetailByEmotion($data[1]);
    if($info) {
        return preg_replace("/\[.+?\]/i","<img src='".__THEME__."/images/expression/".$info['type']."/".$info['filename']."' />",$data[0]);
    }else {
        return $data[0];
    }
}

/**
 * 根据用户昵称获取用户ID [格式化微广播与格式化评论专用]
 *
 * @param array $name
 * @see format()
 * @see formatComment()
 */
function getUserId($name) {
    $info = D('User', 'home')->getUserByIdentifier($name[1], 'uname');

    if ($info) {
        return getUserSpace($info['uid'], 'null', '_blank', "$name[0]", false);
    }else {
        return "<a href=".U('home/user/searchuser',array('k'=>urlencode($name[1])))." target=\"_blank\">".$name[0]."</a>";
    }
}

/**
 * 获取用户的绑定状态
 *
 * @param int    $uid  用户ID
 * @param string $type 平台类型
 */
function bindstate($uid,$type) {
    return M("login")->where("uid=$uid AND type='{$type}'")->count();
}

/**
 * 获取给定URL的短地址
 *
 * @param string $url
 * @return string
 */
function getShortUrl($url){
    return service('ShortUrl')->getShort( htmlspecialchars_decode($url) );
}

/**
 * 将给定用户设为在线
 *
 * @param int $uid
 */
function setOnline($uid) {
    $cookie_name = 'login_time_' . $uid;
    $cookie_time = intval(cookie($cookie_name));
    $now         = time();
    $expire      = 5 * 60; // 有效期: 5min
    if ($cookie_time < ($now - $expire)) {
        //删除7天前登录的用户
        //M('')->execute('DELETE FROM ' . C('DB_PREFIX') . 'user_online where ctime<'.($now-3600*24*7));
        cookie($cookie_name, $now, $expire);
        $sql = 'REPLACE INTO ' . C('DB_PREFIX') . 'user_online (`uid`,`ctime`) VALUES ("' . $uid . '", "' . $now . '")';
        return M('')->execute($sql);
    } else {
        return null;
    }
}

/**
 * 获取当前在线用户数(有效期15分钟)
 *
 * @return int
 */
function getOnlineUserCount() {
    $time = time() - 15 * 60;
    $sql = "SELECT COUNT(*) AS count FROM " . C('DB_PREFIX') . "user_online WHERE `ctime` > ".$time;
    $res = M('')->query($sql);
    return $res[0]['count'];
}


/**
 * 根据access.inc.php检查是否有权访问当前节点(APP_NAME/MODULE_NAME/ACTION_NAME)
 * @return boolean
 */
function canAccess() {
    $acl = C('access');
    return $acl[APP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME] === true
    || $acl[APP_NAME.'/'.MODULE_NAME.'/*'] === true
    || $acl[APP_NAME.'/*/*'] === true;
}

/**
 * 根据应用名获取应用别名
 *
 * @param string $appname
 */
function getAppAlias($appname) {
    global $ts;
    foreach($ts['user_app'] as $apps) {
        foreach($apps as $app) {
            if($app['app_name'] == $appname)
                return $app['app_alias'];
        }
    }
    $app = model('App')->getAppDetailByName($appname);
    return $app['app_alias'];
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() functions causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @since 2.0.0
 *
 * @param array|string $value The array or string to be striped.
 * @return array|string Stripped array (or string in the callback).
 */
function stripslashes_deep($value) {
    if ( is_array($value) ) {
        $value = array_map('stripslashes_deep', $value);
    } elseif ( is_object($value) ) {
        $vars = get_object_vars( $value );
        foreach ($vars as $key=>$data) {
            $value->{$key} = stripslashes_deep( $data );
        }
    } else {
        $value = stripslashes($value);
    }

    return $value;
}

/**
 * 通过循环遍历将对象转换为数组
 *
 * @param object $var
 * @return array
 */
function object_to_array($var) {
    if ( is_object($var) )
        $var = get_object_vars($var);

    if ( is_array($var) )
        $var = array_map('object_to_array', $var);

    return $var;
}

/**
 * 根据给定的省市的代码获取实际地址
 *
 * @param int $province
 * @param int $city
 */
function getLocation($province,$city){
    $l = model('Area')->getAreaTree();

    foreach ($l['provinces'] as $key=>$value){
        $arr['province'][$value['id']] = $value['name'];
        if($value['citys']){
            foreach($value['citys'] as $k=>$v){
                foreach($v as $kk=>$vv){
                    $arr['city'][$value['id']][$kk] = $vv;
                }
            }
        }
    }
    if($province){
        $return = $arr['province'][$province];
        if($city){
            $return= $return.' '.$arr['city'][$province][$city];
        }
    }
    return $return;
}

/**
 * 获取微广播来源
 *
 * @param string $type
 * @param array  $type_data
 */
function getFrom($type, $type_data) {
    $type_data = unserialize($type_data);
    if( $type_data['app_name'] ) {
        $app_alias = getAppAlias($type_data['app_name']);
        if (empty($app_alias)){
            if ($type_data['app_name'] == 'square_video')
                $app_alias = '视频中心';
            elseif ($type_data['app_name'] == 'square_blog')
                $app_alias = '博客中心';
        }
        // title只取前10个字符
	    $title = $type_data['title'];
        mb_strlen($type_data['title'], 'UTF8') > 10 && $type_data['title'] = mb_substr($type_data['title'],0,10,'UTF8') . '...';

        $type_data['title'] = !empty($type_data['title']) ? ' - '.$type_data['title'] : '';
        $type_data['url']	= !empty($type_data['url'])   ? $type_data['url'] : '###';
        $target = substr($type_data['url'], 0, 1) == '#'  ? '_self' : '_blank';
        $html = "<a href=\"{$type_data['url']}\" title=\"$app_alias - {$title}\" target=\"{$target}\">$app_alias{$type_data['title']}</a>";

    }elseif($type_data['source']){
        //站外资源
        // source只取前10个字符
        mb_strlen($type_data['source'], 'UTF8') > 10 && $type_data['source'] = mb_substr($type_data['source'],0,10,'UTF8') . '...';

        $type_data['url']	= !empty($type_data['url'])   ? $type_data['url'] : '###';
        $target = substr($type_data['url'], 0, 1) == '#'  ? '_self' : '_blank';
        $html = "<a href=\"{$type_data['url']}\" target=\"{$target}\">{$type_data['source']}</a>";
    }elseif($type==0){
        $html = '<span>网站</span>';
    }elseif($type==1){
        $html = '<span>手机网页</span>';
    }elseif ($type==2){
        $html = '<span>Android客户端</span>';
    }elseif ($type==3){
        $html = '<span>iPhone客户端</span>';
    }
    return $html;
}

/**
 * 锁定表单
 *
 * @param int $life_time 表单锁的有效时间(秒). 如果有效时间内未解锁, 表单锁自动失效.
 * @return boolean 成功锁定时返回true, 表单锁已存在时返回false
 */
function lockSubmit($life_time = null) {
    if ( isset($_SESSION['LOCK_SUBMIT_TIME']) && intval($_SESSION['LOCK_SUBMIT_TIME']) > time() ) {
        return false;
    }else {
        $life_time = $life_time ? $life_time : $GLOBALS['ts']['site']['max_post_time'];
        $_SESSION['LOCK_SUBMIT_TIME'] = time() + intval($life_time);
        return true;
    }
}

/**
 * 检查表单是否已锁定
 *
 * @return boolean 表单已锁定时返回true, 否则返回false
 */
function isSubmitLocked() {
    return isset($_SESSION['LOCK_SUBMIT_TIME']) && intval($_SESSION['LOCK_SUBMIT_TIME']) > time();
}

/**
 * 表单解锁
 *
 * @return void
 */
function unlockSubmit() {
    unset($_SESSION['LOCK_SUBMIT_TIME']);
}

/**
 * 检查表单是否已锁定
 *
 * @return boolean 表单已锁定时返回true, 否则返回false
 */
function isDuplicateContent($content) {
    $content = md5($content);
    $res = ($_SESSION['LOCK_SUBMIT_CONTENT'] === $content);
    if (!$res) {
        $_SESSION['LOCK_SUBMIT_CONTENT'] = $content;
    }
    return $res;
}

/**
 * 对strip_tags函数的扩展, 可以过滤object, param, embed等来自编辑器的标签
 * @param unknown_type $str
 * @param unknown_type $allowable_tags
 */
function real_strip_tags($str, $allowable_tags) {
    $str = stripslashes(htmlspecialchars_decode($str));
    return strip_tags($str, $allowable_tags);
}

/**
 * 检查是否是以手机浏览器进入(IN_MOBILE)
 */
function isMobile() {
    $mobile = array();
    static $mobilebrowser_list ='Mobile|iPhone|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 获取手机浏览器
    if(preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    }else{
        if(preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }else{
            if($_GET['mobile'] === 'yes') {
                return true;
            }else{
                return false;
            }
        }
    }
}

/**
 *
 */
function isiPhone()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false;
}
/**
 *
 */
function isiPad()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false;
}
/**
 *
 */
function isiOS()
{
    return isiPhone() || isiPad();
}
/**
 *
 */
function isAndroid()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false;
}

/**
 * 获取用户浏览器型号。新加浏览器，修改代码，增加特征字符串.把IE加到12.0 可以使用5-10年了.
 */
function getBrowser(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
        $browser = 'Maxthon';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
        $browser = 'IE12.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
        $browser = 'IE11.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
        $browser = 'IE10.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
        $browser = 'IE9.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
        $browser = 'IE8.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
        $browser = 'IE7.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
        $browser = 'IE6.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
        $browser = 'NetCaptor';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
        $browser = 'Netscape';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
        $browser = 'Lynx';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
        $browser = 'Opera';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
        $browser = 'Google';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
        $browser = 'Firefox';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
        $browser = 'Safari';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
        $browser = 'iphone';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
        $browser = 'iphone';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
        $browser = 'android';
    } else {
        $browser = 'other';
    }
    return $browser;
}
/**
 * 检查给定的用户名是否合法
 *
 * 合法的用户名由2-10位的中英文/数字/下划线/减号组成
 *
 * @param string $username
 * @return boolean
 */
function isLegalUsername( $username ){
    // GB2312: preg_match("/^[".chr(0xa1)."-".chr(0xff)."A-Za-z0-9_-]+$/", $username)
    return preg_match("/^[\x{2e80}-\x{9fff}A-Za-z_-]{1}[\x{2e80}-\x{9fff}A-Za-z0-9_-]+$/u", $username) &&
    mb_strlen($username, 'UTF-8') >= 2 &&
    mb_strlen($username, 'UTF-8') <= 10;
}
/**
 *
 */
function isValidUname($uname)
{
    return isLegalUsername($uname);
}
/**
 *
 */
function isUnameAvailable($uname)
{
    return D('User', 'home')->isUnameAvailable($uname);
}
/**
 *
 */
function isValidPassword($password)
{
    return strlen($password) >= 6 && strlen($password) < 16;
}


/**
 * Adds data to the cache, if the cache key doesn't aleady exist.
 *
 * @since 2.0.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::add()
 *
 * @param int|string $key The cache ID to use for retrieval later
 * @param mixed $data The data to add to the cache store
 * @param string $flag The group to add the cache to
 * @param int $expire When the cache data should be expired
 * @return unknown
 */
function object_cache_add($key, $data, $flag = '', $expire = 0) {
    global $ts_object_cache;

    return $ts_object_cache->add($key, $data, $flag, $expire);
}

/**
 * Removes the cache contents matching ID and flag.
 *
 * @since 2.0.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::delete()
 *
 * @param int|string $id What the contents in the cache are called
 * @param string $flag Where the cache contents are grouped
 * @return bool True on successful removal, false on failure
 */
function object_cache_delete($id, $flag = '') {
    global $ts_object_cache;

    return $ts_object_cache->delete($id, $flag);
}

/**
 * Removes all cache items.
 *
 * @since 2.0.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::flush()
 *
 * @return bool Always returns true
 */
function object_cache_flush() {
    global $ts_object_cache;

    return $ts_object_cache->flush();
}

/**
 * Retrieves the cache contents from the cache by ID and flag.
 *
 * @since 2.0.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::get()
 *
 * @param int|string $id What the contents in the cache are called
 * @param string $flag Where the cache contents are grouped
 * @return bool|mixed False on failure to retrieve contents or the cache
 *		contents on success
 */
function object_cache_get($id, $flag = '') {
    global $ts_object_cache;

    return $ts_object_cache->get($id, $flag);
}

/**
 * Sets up Object Cache Global and assigns it.
 *
 * @since 2.0.0
 * @global ObjectCacheService $ts_object_cache WordPress Object Cache
 */
function object_cache_init() {
    if (!isset($GLOBALS['ts_object_cache']))
        $GLOBALS['ts_object_cache'] = service('ObjectCache');
}

/**
 * Replaces the contents of the cache with new data.
 *
 * @since 2.0.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::replace()
 *
 * @param int|string $id What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $flag Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False if cache ID and group already exists, true on success
 */
function object_cache_replace($key, $data, $flag = '', $expire = 0) {
    global $ts_object_cache;

    return $ts_object_cache->replace($key, $data, $flag, $expire);
}

/**
 * Saves the data to the cache.
 *
 * @since 2.0
 * @uses $ts_object_cache Object Cache Class
 * @see ObjectCacheService::set()
 *
 * @param int|string $id What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $flag Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False if cache ID and group already exists, true on success
 */
function object_cache_set($key, $data, $flag = '', $expire = 0) {
    global $ts_object_cache;

    return $ts_object_cache->set($key, $data, $flag, $expire);
}

function object_cache_merge($key, array $data, $flag = '', $expire = 0) {
    global $ts_object_cache;

    return $ts_object_cache->merge($key, $data, $flag, $expire);
}

/**
 * Adds a group or set of groups to the list of global groups.
 *
 * @since 2.6.0
 *
 * @param string|array $groups A group or an array of groups to add
 */
function object_cache_add_global_groups( $groups ) {
    global $ts_object_cache;

    return $ts_object_cache->add_global_groups($groups);
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @since 2.6.0
 *
 * @param string|array $groups A group or an array of groups to add
 */
function object_cache_add_non_persistent_groups( $groups ) {
    // Default cache doesn't persist so nothing to do here.
    return;
}

/**
 * Reset internal cache keys and structures.  If the cache backend uses global blog or site IDs as part of its cache keys,
 * this functions instructs the backend to reset those keys and perform any cleanup since blog or site IDs have changed since cache init.
 *
 * @since 2.6.0
 *
 * @param string|array $groups A group or an array of groups to add
 */
function object_cache_reset() {
    global $ts_object_cache;

    return $ts_object_cache->reset();
}
/**
 *
 */
function getOAuthToken($uid)
{
    return md5($uid . 'thinksns');
}
/**
 *
 */
function getOAuthTokenSecret()
{
    return md5($_SERVER['REQUEST_TIME'] . 'thinksns');
}
/**
 *
 */
function getCnzz($set = true){
    $cnzz = model('Xdata')->lget('cnzz');
    if (!empty($cnzz) && !empty($cnzz['cnzz_id']) && $set){
        global $ts;
        $ts['cnzz'] = $cnzz;
    }
    return $cnzz;
}
/**
 * uri for iis / apache
 */
function getRequestUri(){
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        } else {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        }
    }
    return $uri;
}


/* -------------------------------------- 自定义方法 ------------------------------------------------------*/

/**
 * 获取用户班级空间
 */
function getClassSpace() {
//     //获取我相关的
//     $ucinfo = arrayKeyToLower($_SESSION['ucInfo']);

//     //如果为老师
//     if($ucinfo['identitytype'] == '2'){
//         $relevant = get_class_by_teacher_uid($ucinfo['uid']);
//     }
//     //如果为学生
//     if($ucinfo['identitytype'] == '3'){
//         $relevant = get_class_by_student_uid($ucinfo['uid']);
//     }
//     //如果为家长
//     if($ucinfo['identitytype'] == '4'){
//     	$relevant = get_class_by_parent_uid($ucinfo['uid']);
//     }
//     if(count($relevant) > 0){
//     	return 1;
//     }else{
//     	return 0;
//     }
		return 0;
   
}

/**
 * 获取用户学科空间
 */
function getCourseSpace() {
// 	//获取我相关的
// 	$ucinfo = arrayKeyToLower($_SESSION['ucInfo']);
// 	//如果为老师
// 	if($ucinfo['identitytype'] == '2'){
// 		$organize = $ucinfo['organize'];
// 		foreach ($organize as $data){
			
// 		}
// 		$relevant['count'] = count($organize);
// 		$relevant['data'] = $organize[0];
// 	}
// 	return $relevant;
	return '';
}

/**
+------------------------------------------------------------------------------
 * Enter 将多维数组转换为一维数组
 * @param 多维数组 $arr
 * author：sunxiaobo
 * date：2012-12-11
+------------------------------------------------------------------------------
 */
function arrayFormat($arr,&$return){
    foreach($arr as $r){
        if(is_array($r)){
            arrayFormat($r, $return);
        }else{
            $return[]=$r;
        }
    }
}

/**
 * 获取给定用户的部门信息
 *
 * @param int $uid
 */
function getUserDept($uid,$isid = false) {
    if(UC_SYNC){
        $map['uid'] = $uid;
        $uc_uid = M('ucenter_user_link')->where($map)->getField('uc_uid');
        //$deptinfo = uc_dept_get_uid($uc_uid);
        $deptinfo = uc_get_department_by_uid($uc_uid);
        if($isid){
            return $deptinfo['id'];
        }else{
            return $deptinfo['departname'];
        }
    }
    return '未知部门';
}

/**
 * 获取给定用户的父级部门信息
 *
 * @param int $uid
 */
function getUserParentDept($uid,$isid = false) {
    if(UC_SYNC){
        $map['uid'] = $uid;
        $uc_uid = M('ucenter_user_link')->where($map)->getField('uc_uid');
        $schoolinfo = uc_get_school_by_uid($uc_uid);
        if($isid){
            return $schoolinfo['id'];
        }else{
            return $schoolinfo['xxmc'];
        }
    }
    return '未知单位';
}

/**
 * getAppIconUrl
 */
function getAppIconUrl($icon,$app='gift'){
    return SITE_URL.'/apps/'.$app.'/Appinfo/'.basename($icon);
}

/**
 * 获取App被安装的统计数据
 */
function getAppCount($appid){
    $map['app_id'] = $appid;
    $appinfo = M('app')->where($map)->find();
    if(intval($appinfo['status'])==1)
        $num = M('user')->count();
    else
        $num = M('user_app')->where($map)->count();
    if($num>999){

    }
    if($num>9999){

    }
    if($num>99999){

    }
    return $num;
}

/**
 * 获取APP名称
 */
function getAppName($appid,$lang='zh') {
    global $ts;
    if(!empty($ts['install_apps'])){
        foreach($ts['install_apps'] as $app){
            if($app['app_id']==$appid){
                if($lang==='zh')
                    return $app['app_alias'];
                else
                    return $app['app_name'];
            }
        }
    }
    if(is_numeric($appid)){
        $map['app_id']=$appid;
        $app = M('app_class')->where($map)->find();
        if(!empty($app)){
            if($lang==='zh')
                return $app['app_alias'];
            else
                return $app['app_name'];
        }else{
            return '未知的应用';
        }
    }
}

/**
 * 获取APP分类名称
 */
function getAppClassName($classid,$lang='zh'){
    if(is_numeric($classid)){
        $map['class_id']=$classid;
        $cla = M('app_class')->where($map)->find();
        if(!empty($cla)){
            if($lang==='zh')
                return $cla['class_name'];
            else
                return $cla['class_name'];
        }else{
            return '未知的分类';
        }
    }
}

/**
+----------------------------------------------------------
 * 执行存储过程方法
+----------------------------------------------------------
 * @param string $sql 调用语句
 * @return array
 * @author 小波
+----------------------------------------------------------
 * 创建时间：2013-3-26 下午4:14:16
+----------------------------------------------------------
 */
function procExecute($sql){
    if (C('DB_TYPE') == 'mysql' ) {
        $rows = array ();
        $db = new mysqli(C('DB_HOST'), C('DB_USER'), C('DB_PWD'), C('DB_NAME'), C('DB_PORT'));
        if (mysqli_connect_errno()){
            exit('Can not connect to MySQL server');
        }
        $db->query("SET NAMES UTF8");
        if($db->real_query($sql)){
            do{
                if($result = $db->store_result()){
                    while ($row = $result->fetch_assoc()){
                        array_push($rows, $row);
                    }
                    $result->close();
                }
            }while($db->next_result());
        }
        $db->close();
    }
    return $rows;
}

/**
 * 通过uc_uid获取用户社区中uid
 * 徐程亮
 */
function getUid($uc_uid){
    $user = M('ucenter_user_link')->where('uc_uid='.$uc_uid)->field('uid')->find();
    return $user['uid'];
}

/**
+----------------------------------------------------------
 * 文件大小单位转换
+----------------------------------------------------------
 * @param int $size 字节数
 * @return string
 * @author 杨志明
+----------------------------------------------------------
 * 创建时间：2013-6-7
+----------------------------------------------------------
 */
function formatBytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

/**
* @Title: ext_check_power
* @Description: 得到有没有相应的权限操作
* @param:  $str 权限代表字母 $str = I,D,U,S... or  i,d,u,s...
* @return 有 = true ,无 = false
* @author RickerYu rickeryu@gridinfo.com.cn
* @小波于2013-7-5修改 
 */

function ext_check_power($str, $testpower=''){
	global $ts;	
	$power =  $ts['userAppLimit'][$ts['_app']]['rolearray'];
	if (!empty($testpower)) {
		$power = $testpower;
	}
	foreach ($power as $value){
		if (strtolower($value) == strtolower($str)){
			return true;
		}
	}
	return false;
}

/**
 +----------------------------------------------------------
 + 获取用户的可操作应用列表
 +----------------------------------------------------------
 + @param	
 + @return	return_type
 + @author	小波 (ZzStudio)
 +----------------------------------------------------------
 + 创建时间：	2013-7-25 下午5:11:03
 +----------------------------------------------------------
 */
function getUserAppLimit($mid){
	if (intval($mid) == 0) {
		return null;
	}
	$userAppLimit = get_userAppLimit_by_uid($mid);
	//重新格式化应用权限下标
	foreach ($userAppLimit as $applimit){
		//将权限去转数组去重复
		$applimit['rolearray'] = array_unique(explode(',', $applimit['roleextend']));
		$data[$applimit['app_name']] = $applimit;
	}
	return $data;
}

/**
+----------------------------------------------------------
 * 获取当前登录用户的identityid
+----------------------------------------------------------
 * @return string identityid
 * @author 杨志明
+----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:41:31
+----------------------------------------------------------
 */
function getCurrentUserIdentity(){
    return $_SESSION['ucInfo']['identityid'] ? $_SESSION['ucInfo']['identityid'] : '';
}
/**
 * 取mac地址
 * 
 * @param $os_type 系统类型
 * @return char
 * 
 * @date 2013-6-27
 * @author lcq
 */
function Getphysical_Addr($os_type){
	$return_array = array(); // 返回带有MAC地址的字串数组  
	$mac_addr = '';  
	switch(strtolower($os_type)){
		case "linux": $return_array = forLinux(); break;  
		case "solaris": break;  
		case "unix": break;  
		case "aix": break;  
		default: $return_array = forWindows(); break;  
	}
	$temp_array = array();  
	foreach($return_array as $value){
		if(preg_match("/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i",$value,  
			$temp_array)){         
			$mac_addr = $temp_array[0];  
			break;  
		}  
	}  
	unset($temp_array);  
	S('mac',$mac_addr,3600*24);//缓存了一天，这里可以设置为变量来配置
	return $mac_addr;  
}  
/**
 * windows系统取mac地址
 * @return char
 * 
 * @date 2013-6-27
 * @author lcq
 */
function forWindows(){
	$return_array = array(); // 返回带有MAC地址的字串数组  
	@exec("ipconfig /all", $return_array);  
	if($return_array){
		return $return_array ; // 返回带有MAC地址的字串数组  ;  
	}else{
		$ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";  
		if(is_file($ipconfig))  
			@exec($ipconfig." /all", $return_array);  
		else
			@exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $return_array);  
		return $return_array;  
	}
}
/**
 * linux系统取mac地址
 * @return char
 * 
 * @date 2013-6-27
 * @author lcq
 */        
function forLinux(){  
	$return_array = array(); // 返回带有MAC地址的字串数组  
	@exec("ifconfig -a", $return_array);  
	return $return_array;  
}
/**
 * 获取授权信息
 * @return char
 * 
 * @date 2013-6-27
 * @author lcq
 */ 
function get_licenseinfo(){

	$lic_info = file_get_contents(SITE_PATH.'\license.zl');
	$lic_info_array = array();
	if(!$lic_info){
		$result['status'] = 1;
		$result['info'] = '授权验证失败，授权文件不存在或授权信息有改动!';
	}else{
		$lic_info = mb_convert_encoding( $lic_info, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' );
		$lic_info = desdecrypt($lic_info,'GridSoft');
		$lic_info_array = json_decode($lic_info,true);
	}
	return $lic_info_array;
	
}
/**
 * 授权验证函数    获取授权信息比较并得出授权结果
 * @return char
 * 
 * @date 2013-6-27
 * @author lcq
 */      
function check_licenseinfo($appname){

	$result = array( 'status' => '','info'	 => '授权验证通过!');//保存验证结果
	
	$licensewhitelist = array('admin', 'home');
	if(in_array($appname,$licensewhitelist)) return $result;	//如果白名单直接返回不用检测
	
	if(!S('lic_info')){ //如果没有license缓存则创建
		$lic_info = file_get_contents(SITE_PATH.'\license.zl');
    	S('lic_info', $lic_info,3600*24);
    }else $lic_info = S('lic_info');
	$lic_info = file_get_contents(SITE_PATH.'\license.zl');
	if(!$lic_info){
		$result['status'] = 1;
		$result['info'] = 'License不存在或错误!';
	}else{
		$lic_info = mb_convert_encoding( $lic_info, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' );
		$lic_info = desdecrypt($lic_info,'GridSoft');
		$lic_info = json_decode($lic_info,true);

		if(is_array($lic_info) && !empty($lic_info)){
		
			$physical_address = Getphysical_Addr(PHP_OS);
			$md5_mac = md5($physical_address.'GridSoft');

			if(isset($lic_info['physicaladdr']) && $md5_mac == $lic_info['physicaladdr']){
				if(isset($lic_info[$appname]) && is_array($lic_info[$appname])){
					if(isset($lic_info[$appname]['endtime']) && time() < strtotime($lic_info[$appname]['endtime'])){
						if(isset($lic_info[$appname]['onlinemembers']) && 200 > $lic_info[$appname]['onlinemembers']){
						//验证通过
						}else{
							$result['status'] = 5;
							$result['info'] = '当前在线人数超出License规定的人数！';
						}
					}else{
						$result['status'] = 4;
						$result['info'] = '该应用的License信息已过期!';
					}
				}else{
					$result['status'] = 3;
					$result['info'] = 'License没有该应用的授权信息!';
				}
			}else{
				$result['status'] = 2;
				$result['info'] = '主机机器码与License信息不符!。';
			}	
		}else{
			$result['status'] = 1;
			$result['info'] = 'License不存在或错误!';
		}
	}
	return $result;
}


function get_rss($url ,$num, $titlelength=''){
	
	header("content-type:text/html; charset=utf-8"); //设置编码
	//echo $url;
	$urldata = cur_url_getdata($url,'','',20);
	
	$rss=simplexml_load_string($urldata);
	$i = 1;
	$result = array();
	foreach($rss->channel->item as $item){
		$list = array();
		$list['title'] =  (String)$item->title;
		if(!empty($titlelength)){
			$list['shorttitle'] =  msubstr((String)$item->title,0,$titlelength);
		}else{
			$list['shorttitle'] = $list['title'];
		}
		$list['link'] =  (String)$item->link;
		$list['description'] =  (String)$item->description;
		preg_match("/src=([^'\"]+)( )/",$list['description'],$list['pic']);//带引号
		foreach ($list['pic'] as $key=>$val){			
			$val = trim($val);
			if(empty($val)){
				unset($list['pic'][$key]);
			}else{
				$list['pic'][$key] = str_replace('src=', '', $val);
				if(strpos($list['pic'][$key],'http://') != 0){
					$list['pic'][$key] = 'http://www.jlju.edu.cn'.$list['pic'][$key];
				}
			}
		}
		$list['description'] = preg_replace('/<img\s+src=(.*?(?:[\.gif|\.jpg])).*?[\/]?>/', '', (String)$item->description);
		$list['description'] = trim(str_replace('<br />', '', $list['description']));
		$list['pubDate'] =  (String)$item->pubDate;
		$list['pubDate'] = explode(' ', $list['pubDate']);
		
		array_push($result, $list);
		$i += 1;
		if($i > $num){
			break;
		}
// 		echo '<h1><a href="'.$item->link .' ">'.$item->title.'</a></h1> ';
// 		echo "<p>" . $item->description . "</p>";
	}
	return $result;
}


/**
 * curl 多线程抓取
 * @param array $array 并行网址
 * @param int $timeout 超时时间
 * @return array
 */
function curl_http($array, $timeout) {
	$res = array ();
	$mh = curl_multi_init();

	foreach ( $array as $k => $url ) {
		$conn[$k] = curl_init($url);
		curl_setopt($conn[$k], CURLOPT_TIMEOUT, $timeout); // 设置超时时间
		curl_setopt($conn[$k], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($conn[$k], CURLOPT_MAXREDIRS, 7); // HTTp定向级别
		curl_setopt($conn[$k], CURLOPT_HEADER, 0); // 这里不要header，加块效率
		curl_setopt($conn[$k], CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
		curl_setopt($conn[$k], CURLOPT_RETURNTRANSFER, 1);
		curl_multi_add_handle($mh, $conn[$k]);
	}
	$active = null;
	// 防止死循环耗死cpu
	do {
		$mrc = curl_multi_exec($mh, $active); // 当无数据，active=true
	} while ( $mrc == CURLM_CALL_MULTI_PERFORM ); // 当正在接受数据时

	while ($active && $mrc == CURLM_OK) {
		if (curl_multi_select($mh) != -1) {
			do {
				$mrc = curl_multi_exec($mh, $active);
			} while ($status == CURLM_CALL_MULTI_PERFORM);
		}
	}

 	foreach ( $array as $k => $url ) {
        $res[$k] = curl_multi_getcontent($conn[$k]);		// 获得返回信息
        //如果不能通过 curl_multi_getcontent 获取数据则通过 curl_exec来获取
        if($res[$k]==NULL) $res[$k] = curl_exec($conn[$k]);
        curl_multi_remove_handle($mh, $conn[$k]);			// 释放资源
    }
	

	curl_multi_close($mh);
	return $res;
}

/**
 +----------------------------------------------------------
 * 远程得到数据信息
 +----------------------------------------------------------
 * @author	RickerYu
 * @param $serverpath - 服务器的地址   $url - 自己拼接的参数
 +----------------------------------------------------------
 * 创建时间：	2013-7-30  下午06:18:32
 +----------------------------------------------------------
 */
function cur_url_getdata($serverpath,$url,$data,$time=10)
{
	//echo ''.$serverpath.$url;
	// 1. 初始化
	$ch = curl_init();
	// 2. 设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, "".$serverpath.$url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //这行是设定curl是否跟随header发送的location
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch,CURLOPT_TIMEOUT,$time);//只需要设置一个秒的数量就可以
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	// 3. 执行并获取HTML文档内容
	$output = curl_exec($ch);
	// 4. 释放curl句柄
	curl_close($ch);
	return $output;

}
/**
 * 显示分享按钮
 *
 * @param $info array 单条记录
 * @param $action string or int 审核管理类型名或顺序值（从0开始）
 * @param $appname string 应用名
 * @return array
 *
 * @date 2013-7-26
 * @author 杨志明
 */
function showShareBtn($tpl_data, $param_data, $info, $action = 0, $appname = '') {
	//取应用表配置信息
	$config = service('ForeAdmin')->getConfig($action, $appname);

	$isAuditApp = service('ForeAdmin')->isAuditApp($appname);

	if(is_array($info)) {
		$status = $info[$config['statusFieldName']];
	} else {
		$status = $info;
	}
	$shareBtn  = !$isAuditApp || $status == $config['pass'] ? '<a href="javascript:void(0);" class="link-act" onClick="_widget_weibo_start(\'\', \''.$tpl_data.'\', \''.$param_data.'\');">分享</a>' : '';

	return $shareBtn;
}

/**
 *
 +----------------------------------------------------------
 * Enter description here ... （日期计算函数）
 +----------------------------------------------------------
 * @param <$givendate = 给的日期，$day = 添加的天数，$mth=添加的月数，$yr=添加的年数>
 * @return string <新的日期>
 * @author RickerYu 2013-11-19
 +----------------------------------------------------------
 */
function add_date($givendate,$day=0,$mth=0,$yr=0) {
	$cd = strtotime($givendate);
	$newdate = date('Y-m-d', mktime(0,0, 0, date('m',$cd)+$mth,date('d',$cd)+$day, date('Y',$cd)+$yr));
	return $newdate;
}
/**
 *
 +----------------------------------------------------------
 * Enter description here ... （比较两个日期相差几天）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return number <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-19
 +----------------------------------------------------------
 */
function diff_date($date1,$date2){
	//计算今天与初始相差几天
	$time = strtotime($date1) - strtotime($date2);
	$day=(int)($time/86400);
	return $day;
}
/**
 *
 +----------------------------------------------------------
 * Enter description here ... （小写数字格式化成大写）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return Ambigous <string> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-19
 +----------------------------------------------------------
 */
function change_big($num, $xq=''){
	$bignum = array('零','一','二','三','四','五','六','七','八','九','十');
	if(!empty($xq)){
		$bignum[7] = '日';
	}
	return $bignum[$num];
}
/**
 *
 +----------------------------------------------------------
 * Enter description here ... （日期格式化操作）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-19
 +----------------------------------------------------------
 */
function format_datetomd($foredate){
	$date = date_create($foredate);
	return  date_format($date, "m\/d");
}


