<?php
/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: client.php 1079 2011-04-02 07:29:36Z zhengqingpeng $
*/
//error_reporting(E_ERROR);

define('IN_UC', TRUE);
define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20110501');
define('UC_ROOT', substr(__FILE__,0, -10));
define('UC_DATADIR', UC_ROOT.'./data/');
define('UC_DATAURL', UC_API.'/data');
define('UC_API_FUNC', UC_CONNECT == 'mysql' ? 'uc_api_mysql' : 'uc_api_post');
$GLOBALS['uc_controls'] = array();
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_addslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = uc_addslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

if(!function_exists('daddslashes')) {

	function daddslashes($string, $force = 0) {
		return uc_addslashes($string, $force);
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_stripslashes($string) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(MAGIC_QUOTES_GPC) {
		return stripslashes($string);
	} else {
		return $string;
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_api_post($module, $action, $arg = array()) {
	$s = $sep = '';
	foreach($arg as $k => $v) {
		$k = urlencode($k);
		if(is_array($v)) {
			$s2 = $sep2 = '';
			foreach($v as $k2 => $v2) {
				$k2 = urlencode($k2);
				$s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
				$sep2 = '&';
			}
			$s .= $sep.$s2;
		} else {
			$s .= "$sep$k=".urlencode(uc_stripslashes($v));
		}
		$sep = '&';
	}
	$postdata = uc_api_requestdata($module, $action, $s);
	return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_api_requestdata($module, $action, $arg='', $extra='') {
	$input = uc_api_input($arg);
	$post = "m=$module&a=$action&inajax=2&release=".UC_CLIENT_RELEASE."&input=$input&appid=".UC_APPID.$extra;
	return $post;
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_api_url($module, $action, $arg='', $extra='') {
	$url = UC_API.'/index.php?'.uc_api_requestdata($module, $action, $arg, $extra);
	return $url;
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_api_input($data) {
	$s = urlencode(uc_authcode($data.'&agent='.md5($_SERVER['HTTP_USER_AGENT'])."&time=".time(), 'ENCODE', UC_KEY));
	return $s;
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_api_mysql($model, $action, $args=array()) {


	global $uc_controls;
	if(empty($uc_controls[$model])) {
		include_once UC_ROOT.'./lib/db.class.php';
		include_once UC_ROOT.'./model/base.php';
		include_once UC_ROOT."./control/$model.php";
		eval("\$uc_controls['$model'] = new {$model}control();");
	}

	if($action{0} != '_') {
		$args = uc_addslashes($args, 1, TRUE);
		$action = 'on'.$action;
		$uc_controls[$model]->input = $args;
		return $uc_controls[$model]->$action($args);
	} else {
		return '';
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_serialize($arr, $htmlon = 0) {
	include_once UC_ROOT.'./lib/xml.class.php';
	return xml_serialize($arr, $htmlon);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_unserialize($s) {
	include_once UC_ROOT.'./lib/xml.class.php';
	return xml_unserialize($s);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_fopen2($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$__times__ = isset($_GET['__times__']) ? intval($_GET['__times__']) + 1 : 1;
	if($__times__ > 2) {
		return '';
	}
	$url .= (strpos($url, '?') === FALSE ? '?' : '&')."__times__=$__times__";
	return uc_fopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	!isset($matches['host']) && $matches['host'] = '';
	!isset($matches['path']) && $matches['path'] = '';
	!isset($matches['query']) && $matches['query'] = '';
	!isset($matches['port']) && $matches['port'] = '';
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	if(function_exists('fsockopen')) {
		$fp = @fsockopen((empty($ip) ? $host : $ip), $port, $errno, $errstr, $timeout);
	} elseif (function_exists('pfsockopen')) {
		$fp = @pfsockopen((empty($ip) ? $host : $ip), $port, $errno, $errstr, $timeout);
	} else {
		$fp = false;
	}
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_app_ls() {
	$return = call_user_func(UC_API_FUNC, 'app', 'ls', array());
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_feed_add($icon, $uid, $username, $title_template='', $title_data='', $body_template='', $body_data='', $body_general='', $target_ids='', $images = array()) {
	return call_user_func(UC_API_FUNC, 'feed', 'add',
		array(  'icon'=>$icon,
			'appid'=>UC_APPID,
			'uid'=>$uid,
			'username'=>$username,
			'title_template'=>$title_template,
			'title_data'=>$title_data,
			'body_template'=>$body_template,
			'body_data'=>$body_data,
			'body_general'=>$body_general,
			'target_ids'=>$target_ids,
			'image_1'=>$images[0]['url'],
			'image_1_link'=>$images[0]['link'],
			'image_2'=>$images[1]['url'],
			'image_2_link'=>$images[1]['link'],
			'image_3'=>$images[2]['url'],
			'image_3_link'=>$images[2]['link'],
			'image_4'=>$images[3]['url'],
			'image_4_link'=>$images[3]['link']
		)
	);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_feed_get($limit = 100, $delete = TRUE) {
	$return = call_user_func(UC_API_FUNC, 'feed', 'get', array('limit'=>$limit, 'delete'=>$delete));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_friend_add($uid, $friendid, $comment='') {
	return call_user_func(UC_API_FUNC, 'friend', 'add', array('uid'=>$uid, 'friendid'=>$friendid, 'comment'=>$comment));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_friend_delete($uid, $friendids) {
	return call_user_func(UC_API_FUNC, 'friend', 'delete', array('uid'=>$uid, 'friendids'=>$friendids));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_friend_totalnum($uid, $direction = 0) {
	return call_user_func(UC_API_FUNC, 'friend', 'totalnum', array('uid'=>$uid, 'direction'=>$direction));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @param number $page
 * @param number $pagesize
 * @param number $totalnum
 * @param number $direction
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:26
 +----------------------------------------------------------
 */
function uc_friend_ls($uid, $page = 1, $pagesize = 10, $totalnum = 10, $direction = 0) {
	$return = call_user_func(UC_API_FUNC, 'friend', 'ls', array('uid'=>$uid, 'page'=>$page, 'pagesize'=>$pagesize, 'totalnum'=>$totalnum, 'direction'=>$direction));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 注册用户到UC - 已解决GBK问题
 +----------------------------------------------------------
 * @param unknown $member
 * @param number $require
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:17
 +----------------------------------------------------------
 */
function uc_user_register($member,$require=0) {
	$_member['username']	= ts_auto_charset($member['username']);
	$_member['password']	= $member['password'];
	$_member['email']		= $member['email'];
	$_member['realname']	= ts_auto_charset($member['realname']);
	$_member['identitytype'] = $member['identitytype'];
	$_member['questionid']	= $member['questionid'];
	$_member['answer']		= $member['answer'];
	$_member['regip']		= $member['regip'];
	$_member['require']		= $require;
    /*
     if($_member['identityid']){
		$_member['identityid'] = $member['identityid'];
	 }
    */
    if($member['identityid']){
        $_member['identityid'] = $member['identityid'];
    }
	return call_user_func(UC_API_FUNC, 'user', 'register', $_member);
}

/**
 +----------------------------------------------------------
 * 从UC获取用户信息 - 已解决GBK问题
 +----------------------------------------------------------
 * @param unknown $username
 * @param unknown $password
 * @param number $isuid
 * @param number $checkques
 * @param string $questionid
 * @param string $answer
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:09
 +----------------------------------------------------------
 */
function uc_user_login($username, $password, $isuid = 0, $checkques = 0, $questionid = '', $answer = '') {
	$username	=	ts_auto_charset($username);
	$answer		=	ts_auto_charset($answer);
	$isuid = intval($isuid);
	$return = call_user_func(UC_API_FUNC, 'user', 'login', array('username'=>$username, 'password'=>$password, 'isuid'=>$isuid, 'checkques'=>$checkques, 'questionid'=>$questionid, 'answer'=>$answer));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * TS登录后同步登录到UC
 +----------------------------------------------------------
 * @param unknown $uid
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:49:00
 +----------------------------------------------------------
 */
function uc_user_synlogin($uid) {
	$uid = intval($uid);
	if(@include UC_ROOT.'./data/cache/apps.php') {
		if(count($_CACHE['apps']) > 0) {
			$return = uc_api_post('user', 'synlogin', array('uid'=>$uid));
		} else {
			$return = '';
		}
	}
	return $return;
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @param unknown $uid
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:48:45
 +----------------------------------------------------------
 */
function uc_user_face($uid) {
	$uid = intval($uid);
	if(@include UC_ROOT.'./data/cache/apps.php') {
		if(count($_CACHE['apps']) > 0) {
			$return = uc_api_post('user', 'getUserFace', array('uid'=>$uid));
		} else {
			$return = '';
		}
	}
	return $return;
}

/**
 +----------------------------------------------------------
 * TS注销登录后同步注销到UC
 +----------------------------------------------------------
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:48:21
 +----------------------------------------------------------
 */
function uc_user_synlogout() {
	if(@include UC_ROOT.'./data/cache/apps.php') {
		if(count($_CACHE['apps']) > 0) {
			$return = uc_api_post('user', 'synlogout', array());
		} else {
			$return = '';
		}
	}
	return $return;
}

/**
 +----------------------------------------------------------
 * 修改UC上的用户名、密码
 +----------------------------------------------------------
 * @param unknown $username
 * @param unknown $oldpw
 * @param unknown $newpw
 * @param unknown $email
 * @param number $ignoreoldpw
 * @param string $questionid
 * @param string $answer
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:48:13
 +----------------------------------------------------------
 */
function uc_user_edit($uid, $oldpw, $newpw, $newemail, $ignoreoldpw = 0, $questionid = '', $answer = '') {
	//$username	=	ts_auto_charset($username);
	$answer		=	ts_auto_charset($answer);
	return call_user_func(UC_API_FUNC, 'user', 'edit', array('uid'=>$uid, 'oldpw'=>$oldpw, 'newpw'=>$newpw, 'newemail'=>$newemail, 'ignoreoldpw'=>$ignoreoldpw, 'questionid'=>$questionid, 'answer'=>$answer));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_delete($uid) {
	return call_user_func(UC_API_FUNC, 'user', 'delete', array('uid'=>$uid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_deleteavatar($uid) {
	uc_api_post('user', 'deleteavatar', array('uid'=>$uid));
}

/**
 +----------------------------------------------------------
 * 根据用户类型ID与用户基础ID判断用户是否已注册
 +----------------------------------------------------------
 * @param unknown $id
 * @param unknown $type
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:48:02
 +----------------------------------------------------------
 */
function uc_user_by_identity($id,$type){
	return call_user_func(UC_API_FUNC, 'user', 'check_identity', array('identityid'=>$id,'identitytype'=>$type));
}

/**
 +----------------------------------------------------------
 * 检查UC上用户是否存在 - 已解决GBK问题
 +----------------------------------------------------------
 * @param unknown $username
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:52
 +----------------------------------------------------------
 */
function uc_user_checkname($username) {
	$username	=	ts_auto_charset($username);
	return call_user_func(UC_API_FUNC, 'user', 'check_username', array('username'=>$username));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_checkemail($email) {
	return call_user_func(UC_API_FUNC, 'user', 'check_email', array('email'=>$email));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_checkuserno($userno) { //检查学生学号是否存在
	return call_user_func(UC_API_FUNC, 'user', 'check_userno', array('userno'=>$userno));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_addprotected($username, $admin='') {
	return call_user_func(UC_API_FUNC, 'user', 'addprotected', array('username'=>$username, 'admin'=>$admin));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_deleteprotected($username) {
	return call_user_func(UC_API_FUNC, 'user', 'deleteprotected', array('username'=>$username));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:26
 +----------------------------------------------------------
 */
function uc_user_getprotected() {
	$return = call_user_func(UC_API_FUNC, 'user', 'getprotected', array('1'=>1));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 从UC上获取用户信息 - 已解决GBK问题 $isuid 0昵称 1:uid 2:emial
 +----------------------------------------------------------
 * @param unknown $username
 * @param number $isuid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:14
 +----------------------------------------------------------
 */
function uc_get_user($username, $isuid=0) {
	$username	=	ts_auto_charset($username);
	$return = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$username, 'isuid'=>$isuid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 合并UC上用户信息 - 已解决GBK问题
 +----------------------------------------------------------
 * @param unknown $oldusername
 * @param unknown $newusername
 * @param unknown $uid
 * @param unknown $password
 * @param unknown $email
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:47:04
 +----------------------------------------------------------
 */
function uc_user_merge($oldusername, $newusername, $uid, $password, $email) {
	$oldusername	=	ts_auto_charset($oldusername);
	$newusername	=	ts_auto_charset($newusername);
	return call_user_func(UC_API_FUNC, 'user', 'merge', array('oldusername'=>$oldusername, 'newusername'=>$newusername, 'uid'=>$uid, 'password'=>$password, 'email'=>$email));
}

/**
 +----------------------------------------------------------
 * 合并UC上用户信息 - 已解决GBK问题
 +----------------------------------------------------------
 * @param unknown $username
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:55
 +----------------------------------------------------------
 */
function uc_user_merge_remove($username) {
	$username	=	ts_auto_charset($username);
	return call_user_func(UC_API_FUNC, 'user', 'merge_remove', array('username'=>$username));
}

function uc_user_getcredit($appid, $uid, $credit) {
	return uc_api_post('user', 'getcredit', array('appid'=>$appid, 'uid'=>$uid, 'credit'=>$credit));
}

/**
 +----------------------------------------------------------
 * 从UC上获取用户信息 - 包括角色职务等
 +----------------------------------------------------------
 * @param unknown $username
 * @param number $isuid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:46
 +----------------------------------------------------------
 */
function uc_get_userinfo($username, $isuid=0) {
	$username	=	ts_auto_charset($username);
	$return = call_user_func(UC_API_FUNC, 'user', 'get_user_info', array('username'=>$username, 'isuid'=>$isuid));
	
	//将返回的结果集中的键全部小写，并保留原来大写的键
	$return = arrayKeyTolower($return,1);

	//获取相关的部门信息
	$deptinfo = uc_get_detpinfo($return['uid'],$return['identitytype']);
	$return['deptinfo'] = $deptinfo;
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 从UC上获取基础用户数据信息
 +----------------------------------------------------------
 * @param unknown $id
 * @param unknown $type
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:36
 +----------------------------------------------------------
 */
function uc_get_baseinfo($id,$type){
	//参数中 $id 为用户的唯一基础数据编号  $type为类型 2为老师4为家长0为学生
	if ($type==2){
		$control = 'teacher';
		$param = array('id'=>$id);
	}else if ($type==4){
		$control = 'parent';
		$param = array('id'=>$id);
	}else{
		$control = 'student';
		$param = array('id'=>$id);
	}
	return call_user_func(UC_API_FUNC, $control, 'get_userinfo_by_id', $param);
}

/**
 +----------------------------------------------------------
 * 从UC上获取我的同学的用户信息 - 包括角色职务等
 +----------------------------------------------------------
 * @param unknown $email
 * @param number $page
 * @param number $pagesize
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:26
 +----------------------------------------------------------
 */
function uc_get_classuser($email,$page=0,$pagesize=0) {
	$user = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$email, 'isuid'=>2));
	if($user)
		$return = call_user_func(UC_API_FUNC, 'user', 'get_class_user', array('deptid'=>$user['DeptID'], 'email'=>$email,'page'=>$page, 'pagesize'=>$pagesize));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 从UC上获取我的班级全体人数
 +----------------------------------------------------------
 * @param unknown $email
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:16
 +----------------------------------------------------------
 */
function uc_get_classnum($email){
	$user = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$email, 'isuid'=>2));
	if($user)
		$return = call_user_func(UC_API_FUNC, 'user', 'get_class_num', array('deptid'=>$user['DeptID']));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 从UC上获取某部门下的人员
 +----------------------------------------------------------
 * @param unknown $deptid
 * @param number $isall
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:46:07
 +----------------------------------------------------------
 */
function uc_get_deptuser($deptid,$isall=0) {
	if(intval($deptid))
		$return = call_user_func(UC_API_FUNC, 'user', 'get_dept_user', array('deptid'=>$deptid, 'isall'=>$isall));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 从UC上获取学生基础数据中的某人信息
 +----------------------------------------------------------
 * @param unknown $userno
 * @param number $type
 * @param string $name
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:45:52
 +----------------------------------------------------------
 */
function uc_baseinfo_by_userno($userno,$type=0,$name='') {
	//参数中 $userno 为用户的唯一编号 学生为学号 老师为身份证号  $type为类型 0为学生 1为老师
	if ($type==1){
		$control = 'teacher';
		$param = array('userno'=>$userno);
	}else if ($type==2){
		$control = 'parent';
		$param = array('userno'=>$userno,'name'=>$name);
	}else{
		$control = 'student';
		$param = array('userno'=>$userno);
	}
	return call_user_func(UC_API_FUNC, $control, 'get_userinfo_by_userno', $param);
}
/**
 +----------------------------------------------------------
 * 根据学生与家长的姓名获取学生的基础信息
 +----------------------------------------------------------
 * @param string $stuName 学生姓名
 * @param string $parName 家长姓名
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-2 上午9:49:02
 +----------------------------------------------------------
 */
function uc_baseinfo_by_name($stuName,$parName){
	$param['stuname'] = $stuName;
	$param['parname'] = $parName;
	return call_user_func(UC_API_FUNC, 'student', 'get_userinfo_by_name', $param);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_location($uid, $newpm = 0) {
	$apiurl = uc_api_url('pm_client', 'ls', "uid=$uid", ($newpm ? '&folder=newbox' : ''));
	@header("Expires: 0");
	@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("location: $apiurl");
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_checknew($uid, $more = 0) {
	$return = call_user_func(UC_API_FUNC, 'pm', 'check_newpm', array('uid'=>$uid, 'more'=>$more));
	return (!$more || UC_CONNECT == 'mysql') ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_send($fromuid, $msgto, $subject, $message, $instantly = 1, $replypmid = 0, $isusername = 0, $type = 0) {
	if($instantly) {
		$replypmid = @is_numeric($replypmid) ? $replypmid : 0;
		return call_user_func(UC_API_FUNC, 'pm', 'sendpm', array('fromuid'=>$fromuid, 'msgto'=>$msgto, 'subject'=>$subject, 'message'=>$message, 'replypmid'=>$replypmid, 'isusername'=>$isusername, 'type' => $type));
	} else {
		$fromuid = intval($fromuid);
		$subject = rawurlencode($subject);
		$msgto = rawurlencode($msgto);
		$message = rawurlencode($message);
		$replypmid = @is_numeric($replypmid) ? $replypmid : 0;
		$replyadd = $replypmid ? "&pmid=$replypmid&do=reply" : '';
		$apiurl = uc_api_url('pm_client', 'send', "uid=$fromuid", "&msgto=$msgto&subject=$subject&message=$message$replyadd");
		@header("Expires: 0");
		@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		@header("location: ".$apiurl);
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_delete($uid, $folder, $pmids) {
	return call_user_func(UC_API_FUNC, 'pm', 'delete', array('uid'=>$uid, 'pmids'=>$pmids));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_deleteuser($uid, $touids) {
	return call_user_func(UC_API_FUNC, 'pm', 'deleteuser', array('uid'=>$uid, 'touids'=>$touids));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_deletechat($uid, $plids, $type = 0) {
	return call_user_func(UC_API_FUNC, 'pm', 'deletechat', array('uid'=>$uid, 'plids'=>$plids, 'type'=>$type));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_readstatus($uid, $uids, $plids = array(), $status = 0) {
	return call_user_func(UC_API_FUNC, 'pm', 'readstatus', array('uid'=>$uid, 'uids'=>$uids, 'plids'=>$plids, 'status'=>$status));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_list($uid, $page = 1, $pagesize = 10, $folder = 'inbox', $filter = 'newpm', $msglen = 0) {
	$uid = intval($uid);
	$page = intval($page);
	$pagesize = intval($pagesize);
	$return = call_user_func(UC_API_FUNC, 'pm', 'ls', array('uid'=>$uid, 'page'=>$page, 'pagesize'=>$pagesize, 'filter'=>$filter, 'msglen'=>$msglen));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_ignore($uid) {
	$uid = intval($uid);
	return call_user_func(UC_API_FUNC, 'pm', 'ignore', array('uid'=>$uid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_view($uid, $pmid = 0, $touid = 0, $daterange = 1, $page = 0, $pagesize = 10, $type = 0, $isplid = 0) {
	$uid = intval($uid);
	$touid = intval($touid);
	$page = intval($page);
	$pagesize = intval($pagesize);
	$pmid = @is_numeric($pmid) ? $pmid : 0;
	$return = call_user_func(UC_API_FUNC, 'pm', 'view', array('uid'=>$uid, 'pmid'=>$pmid, 'touid'=>$touid, 'daterange'=>$daterange, 'page' => $page, 'pagesize' => $pagesize, 'type'=>$type, 'isplid'=>$isplid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_view_num($uid, $touid, $isplid) {
	$uid = intval($uid);
	$touid = intval($touid);
	$isplid = intval($isplid);
	return call_user_func(UC_API_FUNC, 'pm', 'viewnum', array('uid' => $uid, 'touid' => $touid, 'isplid' => $isplid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_viewnode($uid, $type, $pmid) {
	$uid = intval($uid);
	$type = intval($type);
	$pmid = @is_numeric($pmid) ? $pmid : 0;
	$return = call_user_func(UC_API_FUNC, 'pm', 'viewnode', array('uid'=>$uid, 'type'=>$type, 'pmid'=>$pmid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_chatpmmemberlist($uid, $plid = 0) {
	$uid = intval($uid);
	$plid = intval($plid);
	$return = call_user_func(UC_API_FUNC, 'pm', 'chatpmmemberlist', array('uid'=>$uid, 'plid'=>$plid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_kickchatpm($plid, $uid, $touid) {
	$uid = intval($uid);
	$plid = intval($plid);
	$touid = intval($touid);
	return call_user_func(UC_API_FUNC, 'pm', 'kickchatpm', array('uid'=>$uid, 'plid'=>$plid, 'touid'=>$touid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_appendchatpm($plid, $uid, $touid) {
	$uid = intval($uid);
	$plid = intval($plid);
	$touid = intval($touid);
	return call_user_func(UC_API_FUNC, 'pm', 'appendchatpm', array('uid'=>$uid, 'plid'=>$plid, 'touid'=>$touid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_blackls_get($uid) {
	$uid = intval($uid);
	return call_user_func(UC_API_FUNC, 'pm', 'blackls_get', array('uid'=>$uid));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_blackls_set($uid, $blackls) {
	$uid = intval($uid);
	return call_user_func(UC_API_FUNC, 'pm', 'blackls_set', array('uid'=>$uid, 'blackls'=>$blackls));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_blackls_add($uid, $username) {
	$uid = intval($uid);
	return call_user_func(UC_API_FUNC, 'pm', 'blackls_add', array('uid'=>$uid, 'username'=>$username));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_pm_blackls_delete($uid, $username) {
	$uid = intval($uid);
	return call_user_func(UC_API_FUNC, 'pm', 'blackls_delete', array('uid'=>$uid, 'username'=>$username));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_domain_ls() {
	$return = call_user_func(UC_API_FUNC, 'domain', 'ls', array('1'=>1));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_credit_exchange_request($uid, $from, $to, $toappid, $amount) {
	$uid = intval($uid);
	$from = intval($from);
	$toappid = intval($toappid);
	$to = intval($to);
	$amount = intval($amount);
	return uc_api_post('credit', 'request', array('uid'=>$uid, 'from'=>$from, 'to'=>$to, 'toappid'=>$toappid, 'amount'=>$amount));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_tag_get($tagname, $nums = 0) {
	$return = call_user_func(UC_API_FUNC, 'tag', 'gettag', array('tagname'=>$tagname, 'nums'=>$nums));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_avatar($uid, $type = 'virtual', $returnhtml = 1) {
	$uid = intval($uid);
	$uc_input = uc_api_input("uid=$uid");
	$uc_avatarflash = UC_API.'/images/camera.swf?inajax=1&appid='.UC_APPID.'&input='.$uc_input.'&agent='.md5($_SERVER['HTTP_USER_AGENT']).'&ucapi='.urlencode(str_replace('http://', '', UC_API)).'&avatartype='.$type.'&uploadSize=2048';
	if($returnhtml) {
		return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="450" height="253" id="mycamera" align="middle">
			<param name="allowScriptAccess" value="always" />
			<param name="scale" value="exactfit" />
			<param name="wmode" value="transparent" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="movie" value="'.$uc_avatarflash.'" />
			<param name="menu" value="false" />
			<embed src="'.$uc_avatarflash.'" quality="high" bgcolor="#ffffff" width="450" height="253" name="mycamera" align="middle" allowScriptAccess="always" allowFullScreen="false" scale="exactfit"  wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>';
	} else {
		return array(
			'width', '450',
			'height', '253',
			'scale', 'exactfit',
			'src', $uc_avatarflash,
			'id', 'mycamera',
			'name', 'mycamera',
			'quality','high',
			'bgcolor','#ffffff',
			'menu', 'false',
			'swLiveConnect', 'true',
			'allowScriptAccess', 'always'
		);
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_mail_queue($uids, $emails, $subject, $message, $frommail = '', $charset = 'gbk', $htmlon = FALSE, $level = 1) {
	return call_user_func(UC_API_FUNC, 'mail', 'add', array('uids' => $uids, 'emails' => $emails, 'subject' => $subject, 'message' => $message, 'frommail' => $frommail, 'charset' => $charset, 'htmlon' => $htmlon, 'level' => $level));
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_check_avatar($uid, $size = 'middle', $type = 'virtual') {
	$url = UC_API."/avatar.php?uid=$uid&size=$size&type=$type&check_file_exists=1";
	$res = uc_fopen2($url, 500000, '', '', TRUE, UC_IP, 20);
	if($res == 1) {
		return 1;
	} else {
		return 0;
	}
}
/**
 +----------------------------------------------------------
 * 未注释
 +----------------------------------------------------------
 * @return Ambigous <string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:39
 +----------------------------------------------------------
 */
function uc_check_version() {
	$return = uc_api_post('version', 'check', array());
	$data = uc_unserialize($return);
	return is_array($data) ? $data : $return;
}

/**
 +----------------------------------------------------------
 * dept table
 +----------------------------------------------------------
 * @param number $childid
 * @param string $parentid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:29
 +----------------------------------------------------------
 */
function uc_dept_list($childid=0, $parentid='') {
	$childid = intval($childid);
	$parentid = intval($parentid);
	$return = call_user_func(UC_API_FUNC, 'dept', 'ls', array('childid'=>$childid, 'parentid'=>$parentid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 +----------------------------------------------------------
 * 通用学生姓名与家长姓名获取学号等信息
 +----------------------------------------------------------
 * @param unknown $stuName
 * @param string $parentName
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:17
 +----------------------------------------------------------
 */
function uc_get_student_by_name($stuName,$parentName = null){
	$stuName = trim($stuName);
	if ($parentName != null){
		$parentName = trim($parentName);
	}
	$return = call_user_func(UC_API_FUNC, 'student', 'get_student_by_name', array('stuName'=>$stuName,'parentName'=>$parentName));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * role table
 +----------------------------------------------------------
 * @param number $isreg
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:44:07
 +----------------------------------------------------------
 */
function uc_role_list($isreg=0) {
	$isreg = intval($isreg);
	$return = call_user_func(UC_API_FUNC, 'role', 'ls', array('isreg'=>$isreg));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * identity table
 +----------------------------------------------------------
 * @param string $order
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:43:45
 +----------------------------------------------------------
 */
function uc_identity_list($order=''){
	$return = call_user_func(UC_API_FUNC, 'identity', 'ls', array('order'=>$order));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据用户的ID,昵称,Email获取用户身份角色信息
 +----------------------------------------------------------
 * @param string $username ID or 昵称 or Email
 * @param number $isuid 0：ID，1：昵称，2：Email
 * @return Ambigous 查询后的结果
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:59:31
 +----------------------------------------------------------
 */
function uc_get_identity_by_uid($username,$isuid=0){
	$return = call_user_func(UC_API_FUNC, 'identity', 'get_identity_info', array('username'=>$username,'isuid'=>$isuid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据UC用户的ID获取用户学校信息
 +----------------------------------------------------------
 * @param string $username ID or 昵称 or Email
 * @param number $isuid 0：ID，1：昵称，2：Email
 * @return Ambigous 查询后的结果
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-15 上午9:59:31
 +----------------------------------------------------------
 */
function uc_get_school_by_uid($uid){
	$return = call_user_func(UC_API_FUNC, 'school', 'get_school_info', array('uid'=>$uid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据UC用户的ID来获取相关的部门信息
 +----------------------------------------------------------
 * @param Integer $uid UC用户ID
 * @return array 查询后的结果
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-15 上午11:16:06
 +----------------------------------------------------------
 */
function uc_get_department_by_uid($uid){
	$member = call_user_func(UC_API_FUNC, 'user', 'get_user', array('username'=>$uid, 'isuid'=>1));
	$member = arrayKeyTolower($member,1);
	$identitytype = $member['identitytype'];
	$identityid = $member['identityid'];
	switch ($identitytype){
		case "2": 	//老师获取部门
			$return = call_user_func(UC_API_FUNC, 'dept', 'get_departinfo_by_uid', array('uid'=>$uid));
			break;
		case "3": 	//学生获取年级
			$return = call_user_func(UC_API_FUNC, 'classinfo', 'get_classinfo_by_uid', array('uid'=>$uid));
			break;
		case "4": 	//家长
			
			break;
		default:
	}
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过学校ID获取用户列表
 +----------------------------------------------------------
 * @param integer $sid 学校ID
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-15 下午5:03:41
 +----------------------------------------------------------
 */
function uc_get_userlist_by_sid($sid){
	$return = call_user_func(UC_API_FUNC, 'user', 'get_userlist_by_sid', array('sid'=>$sid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过班级ID获取用户列表
 +----------------------------------------------------------
 * @param integer $sid 学校ID
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-16 下午5:03:41
 +----------------------------------------------------------
 */
function uc_get_userlist_by_cid($cid){
	$return = call_user_func(UC_API_FUNC, 'user', 'get_userlist_by_cid', array('cid'=>$cid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过老师ID获取相关的学校及班级信息
 +----------------------------------------------------------
 * @param intval $tid 老师ID
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-26 上午9:59:45
 +----------------------------------------------------------
 */
function uc_get_space_teacher($tid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_teacher_class_course', array('id'=>$tid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

function uc_get_teacher_class_spacenum($tid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_teacher_class_spacenum', array('id'=>$tid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过老师identityid获取任班主任身份的班级
 +----------------------------------------------------------
 * @param intval $identityid 老师identityid
 * @return array
 * @author 小朱
 +----------------------------------------------------------
 * 创建时间：2013-3-26 上午9:59:45
 +----------------------------------------------------------
 */
function uc_get_adviserclassids_by_identityid($identityid){
	$return = call_user_func(UC_API_FUNC, 'teacher_class', 'get_adviserclassids_by_identityid', array('identityid'=>$identityid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过老师ID获取相关的任课信息(去重复列)
 +----------------------------------------------------------
 * @param intval $identityid 老师identityid
 * @return array
 * @author 小朱
 +----------------------------------------------------------
 * 创建时间：2013-3-26 上午9:59:45
 +----------------------------------------------------------
 */
function uc_get_course_by_identityid($identityid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_course_by_identityid', array('identityid'=>$identityid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据老师identityid和课程courseid获取老师代课班级列表
 +----------------------------------------------------------
 * @param	int $courseid 课程id
 * @param	int $identityid 老师identityid
 * @return	array
 * @author	小朱 2013-3-30
 +----------------------------------------------------------
 * 创建时间：	2013-3-30 下午03:55:16
 +----------------------------------------------------------
 */
function uc_get_classids_by_courseid($courseid,$identityid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_classids_by_courseid', array('courseid'=>$courseid,'identityid'=>$identityid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 通过identityid获取已经注册的老师信息
 +----------------------------------------------------------
 * @param	int $identityid 教师identityid
 * @return	return_type <返回类型(void的方法就不用该选项)>
 * @author	小朱 2013-4-7
 +----------------------------------------------------------
 * 创建时间：	2013-4-7 下午06:26:16
 +----------------------------------------------------------
 */
function uc_get_related_by_identityid($identityid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_related_by_identityid', array('identityid'=>$identityid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过课程id获取课程名称
 +----------------------------------------------------------
 * @param	int courseid 课程id
 * @return	string xkmc 课程名称
 * @author	小朱 2013-4-1
 +----------------------------------------------------------
 * 创建时间：	2013-4-1 上午10:30:14
 +----------------------------------------------------------
 */
function uc_get_xkmc_by_courseid($courseid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_xkmc_by_courseid', array('courseid'=>$courseid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 学生通过班级id获取所学课程
 +----------------------------------------------------------
 * @param intval $classid 班级id
 * @return array
 * @author 小朱
 +----------------------------------------------------------
 * 创建时间：2013-3-26 上午9:59:45
 +----------------------------------------------------------
 */
function uc_get_course_by_classid($classid){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_course_by_classid', array('classid'=>$classid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 通过学生ID获取相关的学校及班级信息
 +----------------------------------------------------------
 * @param intval $sid 学生ID
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-26 上午10:23:06
 +----------------------------------------------------------
 */
function uc_get_space_student($sid){
	$return[] = call_user_func(UC_API_FUNC, 'student', 'get_userinfo_by_id', array('id'=>$sid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 从UC上获取用户身份列表
 +----------------------------------------------------------
 * @param number $identityid
 * @param number $isreg
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:40:42
 +----------------------------------------------------------
 */
function uc_get_users_by_identity($identityid=0, $isreg=0) {
	$return = call_user_func(UC_API_FUNC, 'user', 'get_users_by_identity', array('identityid'=>$identityid, 'isreg'=>$isreg));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取某部门类型下的所有部门
 +----------------------------------------------------------
 * @param number $typeid
 * @param number $parentid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:40:33
 +----------------------------------------------------------
 */
function uc_get_dept($typeid=0,$parentid=0){
	$return = call_user_func(UC_API_FUNC, 'dept', 'get_dept_by_where', array('typeid'=>$typeid, 'parentid'=>$parentid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据用户ID获取学校年级班级等职能部门信息
 +----------------------------------------------------------
 * @param unknown $uid
 * @param unknown $type
 * @return mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:40:19
 +----------------------------------------------------------
 */
function uc_get_detpinfo($uid,$type){
	//参数中 $id 为用户的唯一基础数据编号  $type为类型 2为老师4为家长0为学生
	if ($type==2){
		$control = 'teacher';
		$param = array('uid'=>$uid);
	}else if ($type==4){
		$control = 'parent';
		$param = array('uid'=>$uid);
	}else{
		$control = 'student';
		$param = array('uid'=>$uid);
	}
	return call_user_func(UC_API_FUNC, $control, 'get_deptinfo_by_uid', $param);
}

/**
 +----------------------------------------------------------
 * 获取是否是班主任
 +----------------------------------------------------------
 * @param unknown $deptid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:40:07
 +----------------------------------------------------------
 */
function uc_get_users_by_charge($deptid){
	$return = call_user_func(UC_API_FUNC, 'dept', 'get_dept_by_manage', array('deptid'=>$deptid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据deptid获取用户列表
 +----------------------------------------------------------
 * @param unknown $deptid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:59
 +----------------------------------------------------------
 */
function uc_get_users_by_deptid($deptid){
	$return = call_user_func(UC_API_FUNC, 'user', 'get_users_by_deptid', array('deptid'=>$deptid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据deptid获取用户数量count
 +----------------------------------------------------------
 * @param unknown $deptid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:48
 +----------------------------------------------------------
 */
function uc_get_usercount_by_deptid($deptid){
	$return = call_user_func(UC_API_FUNC, 'user', 'get_usercount_by_deptid', array('deptid'=>$deptid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取班主任信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:34
 +----------------------------------------------------------
 */
function uc_class_adviser_get_id($id){
	$id = intval($id);
	$return=call_user_func(UC_API_FUNC, 'teacher_class', 'get_class_adviser_ById', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取班级信息基本信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:26
 +----------------------------------------------------------
 */
function uc_class_get_id($id){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'classinfo', 'get_classinfo_ById', array('id'=>$id));
	$return['bzrxm']=call_user_func(UC_API_FUNC, 'teacher_class', 'get_class_teacher_ById', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取班级信息基本信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:18
 +----------------------------------------------------------
 */
function uc_class_get_id_count($id){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'classinfo', 'get_classcount_ById', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取班级男女生信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:39:09
 +----------------------------------------------------------
 */
function uc_studentcount_get_id($id){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'student', 'get_studentcount_ById', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据班级id获取班级内学生信息
 +----------------------------------------------------------
 * @param	int $classid 班级id
 * @author	小朱 2013-4-2
 +----------------------------------------------------------
 * 创建时间：	2013-4-2 下午04:59:13
 +----------------------------------------------------------
 */
function uc_student_get_id($classid){
	$classid = intval($classid);
	$return = call_user_func(UC_API_FUNC, 'student', 'get_student_ById', array('classid'=>$classid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取班级学生信息
 +----------------------------------------------------------
 * @param unknown $id
 * @param unknown $name
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:38:57
 +----------------------------------------------------------
 */
function uc_student_get_id_byname($id,$name){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'student', 'get_student_By_IdandName', array('id'=>$id,'name'=>$name));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取班级学生信息
 +----------------------------------------------------------
 * @param unknown $id
 * @param unknown $identityids
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:38:47
 +----------------------------------------------------------
 */
function uc_noseat_student_get_id($id,$identityids){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'student', 'get_noseat_student_ById', array('id'=>$id,'identityids'=>$identityids));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取班级当月过生日人的信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:38:40
 +----------------------------------------------------------
 */
function uc_birth_student_get_id($id){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'student', 'get_birthday_student_ById', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取班级教师信息
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:38:30
 +----------------------------------------------------------
 */
function uc_teacher_get_id($id){
	$id = intval($id);
	$return = call_user_func(UC_API_FUNC, 'teacher_class', 'get_teacher_get_id', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 从UC上通过老师id获取老师所有教过的班级
 +----------------------------------------------------------
 * @param unknown $id
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:38:05
 +----------------------------------------------------------
 */
function uc_get_classbyid($id){
		$return = call_user_func(UC_API_FUNC, 'user', 'get_classbyid', array('id'=>$id));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * 
 +----------------------------------------------------------
 * 从UC上获取已注册的学校、班级数
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-27 上午9:37:51
 +----------------------------------------------------------
 */
function uc_get_schoolnum(){
		$return = call_user_func(UC_API_FUNC, 'user', 'get_school_num');
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取全部小学
 +----------------------------------------------------------
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午3:45:45
 +----------------------------------------------------------
 */
function uc_get_small_school(){
	return uc_get_schoollist(1);
}

/**
 +----------------------------------------------------------
 * 获取全部初中
 +----------------------------------------------------------
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午3:45:45
 +----------------------------------------------------------
 */
function uc_get_middle_school(){
	return uc_get_schoollist(2);
}

/**
 +----------------------------------------------------------
 * 获取全部高中
 +----------------------------------------------------------
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午3:45:45
 +----------------------------------------------------------
 */
function uc_get_high_school(){
	return uc_get_schoollist(3);
}

/**
 +----------------------------------------------------------
 * 获取全部学校
 +----------------------------------------------------------
 * @return array
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午3:45:45
 +----------------------------------------------------------
 */
function uc_get_all_school(){
	return uc_get_schoollist();
}
/**
 +----------------------------------------------------------
 * 获取学校列表
 +----------------------------------------------------------
 * @param int $type 0:全部 | 1:小学 | 2:初中 | 3:高中
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午3:42:25
 +----------------------------------------------------------
 */
function uc_get_schoollist($type=0){
	$return = call_user_func(UC_API_FUNC, 'school', 'get_school_list',array('type'=>$type,'fields'=>array('id','xxmc')));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据学校ID获取年级列表
 +----------------------------------------------------------
 * @param number $sid
 * @param number $xd 学段ID 0:全部 1:小学 2:中学 3:高中
 * @return Ambigous <mixed, string, multitype:, unknown>|multitype:
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午5:16:51
 +----------------------------------------------------------
 */
function uc_get_grades_by_sid($sid=0,$xd=0){
	if ($sid>0) {
		$map['sid'] = $sid;
		switch ($xd){
			case 1:
				$map['xd'] = 21;
				break;
			case 2:
				$map['xd'] = 31;
				break;
			case 3:
				$map['xd'] = 34;
				break;
			default:
				$map['xd'] = 0;
		}
		$return = call_user_func(UC_API_FUNC, 'grade', 'get_grade_list',$map);
		return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);;
	}
	return array();
}

/**
 +----------------------------------------------------------
 * 获取某学校下班级列表
 +----------------------------------------------------------
 * @param unknown $sid
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午5:34:24
 +----------------------------------------------------------
 */
function uc_get_classllist_by_sid($sid,$nj){
	$return = call_user_func(UC_API_FUNC, 'classinfo', 'get_classlist_by_sid',array('sid'=>$sid,'nj'=>$nj));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 获取某学校某年级下的班级信息
 +----------------------------------------------------------
 * @param number $nj
 * @param number $schoolid
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-23 下午5:34:39
 +----------------------------------------------------------
 */
function uc_get_gradclass_by_id($nj=0, $sid=0,$xd=0){
	if ($xd) {
		switch ($xd){
			case '1':
				$xd = '21';
				break;
			case '2':
				$xd = '31';
				break;
			case '3':
				$xd = '34';
				break;
			default:
		}
	}
	if (intval($nj)>0 && intval($sid)>0) {
		$return = call_user_func(UC_API_FUNC, 'classinfo', 'get_classlist_by_sid',array('sid'=>$sid,'nj'=>$nj,'xd'=>$xd));
		return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
	}else{
		return 0;
	}
}

/**
 +----------------------------------------------------------
 * 获取普教相关的学科信息
 +----------------------------------------------------------
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-1 下午3:56:04
 +----------------------------------------------------------
 */
function uc_get_esn_course(){
	$return = call_user_func(UC_API_FUNC, 'view', 'get_esn_course',array());
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 根据学段ID获取年级信息
 +----------------------------------------------------------
 * @param int $xdid 学段ID 21:小学 31:初中 34:高中
 * @return Ambigous <mixed, string, multitype:, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-1 下午4:45:33
 +----------------------------------------------------------
 */
function uc_get_grades_by_xd($xdid){
	$return = call_user_func(UC_API_FUNC, 'grade', 'get_grades_by_xd',array('xdid'=>$xdid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/*menu block
function uc_menu_list() {
		$return = call_user_func(UC_API_FUNC, 'menu', 'ls');
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
*/
/**
 +----------------------------------------------------------
 * 根据教师的UC的uid获取教师的所在学校的所有部门(选取老师)
 +----------------------------------------------------------
 * @param integer $uid 教师UID
 * @return array
 * @author 小伟 2013-3-19
 +----------------------------------------------------------
 */
function uc_get_teacher_depts($uid){
	$return = call_user_func(UC_API_FUNC, 'teacher', 'get_dept_list',array('uid'=>$uid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
}
/**
 +----------------------------------------------------------
 * 根据部门deptId获取这个部门里所有的老师信息
 +----------------------------------------------------------
 * @param integer $deptId
 * @return array
 * @author 小伟 2013-3-19
 +----------------------------------------------------------
 */
function uc_get_teachers_by_deptid($deptId,$schoolId){
	$return = call_user_func(UC_API_FUNC, 'teacher', 'get_teachers_list',array('schoolId'=>$schoolId,'deptId'=>$deptId));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
}
/**
 +----------------------------------------------------------
 * 根据教师的UC的uid获取教师的所在学校的所有班级结构(选取学生)
 +----------------------------------------------------------
 * @param integer $uid 教师UID
 * @return array
 * @author 小伟 2013-3-22
 +----------------------------------------------------------
 */
function uc_get_student_depts($uid){
	$return = call_user_func(UC_API_FUNC, 'student', 'get_grade_list',array('uid'=>$uid));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);		
}
/**
 +----------------------------------------------------------
 * 根据班级ID和学校ID获取学生名字
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author 小伟 2013-3-23
 +----------------------------------------------------------
 */
function uc_get_students_by_deptid($classId,$schoolId){
	$return = call_user_func(UC_API_FUNC, 'student', 'get_student_list',array('schoolId'=>$schoolId,'classId'=>$classId));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
}
/**
 +----------------------------------------------------------
 * 根据identityID获取会员的email
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author 小伟 2013-3-23
 +----------------------------------------------------------
 */
function uc_get_email_by_identityid($identityType,$identityId){
	$return = call_user_func(UC_API_FUNC, 'identity', 'get_member_email',array('identityId'=>$identityId,'identityType'=>$identityType));
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);		
}

/**
+----------------------------------------------------------
 *根据classid获取班内学生列表
+----------------------------------------------------------
 * @param classid
 * @return 返回学生的id，头像,名字.
 * @author 徐程亮
+----------------------------------------------------------
 */
function uc_get_students_by_classid($cid){
    $return = call_user_func(UC_API_FUNC, 'user', 'get_class_members',array('cid'=>$cid));
    return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
+----------------------------------------------------------
 *根据家长id获取学生信息
+----------------------------------------------------------
 * @param  pid 家长id
 * @return 返回学生的基本信息.
 * @author 徐程亮
+----------------------------------------------------------
 */

function uc_get_studentInfo_by_pid($pid){
    $return = call_user_func(UC_API_FUNC, 'user', 'get_student_info',array('pid'=>$pid));
    return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


 
function uc_get_course_by_stid($memberid){
	$return = call_user_func(UC_API_FUNC, 'course', 'get_course_by_uid',array('memberid'=>$memberid));
	return $return;
}

/**
+----------------------------------------------------------
 *根据学生id获取学生详细信息
+----------------------------------------------------------
 * @param  sid 学生ID
 * @return 返回学生的基本信息.
 * @author 徐程亮
+----------------------------------------------------------
 */
function uc_get_studentDetail_by_sid($sid){
    $return = call_user_func(UC_API_FUNC, 'user', 'get_studentDetail_info',array('sid'=>$sid));
    return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 *
* @Title: uc_get_course_by_uid
* @Description: 得到当前登录用户的课程信息安排
* @param @return 数组
* @return
* @author Ricker lhyfe@sohu.com
 */

function uc_get_all_by_sfzh($sfzh){
	return get_all_by_sfzh($sfzh);
}
?>