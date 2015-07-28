<?php
/* Iweb产品配置文件 */
if(!$IWEB_IM_IN) {
	die("Hacking attempt");
}

//语言包参数，目前参数值zh(简体中文)、ft(繁体中文)
$langPackagePara="zh";

date_default_timezone_set("Asia/Chongqing");

// 站点配置
$webRoot=str_replace("\\","/",dirname(__FILE__))."/";

// 支持库配置
$baseLibsPath="iweb_mini_lib/";

// 聊天记录拆分的大小 单位字节
$txt_split_lenght = 1024;

// 多长时间不动判断不在线时间 单位秒
$offline_time = 600;

// session 前缀。请与您的主系统保持一致
$session_prefix = "";

// 案例校验码前缀
$verify_prefix = "5rgqw2";

/* 共享数据配置 */
// 站点地址
if ('80' == $_SERVER["SERVER_PORT"]){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
}else{ 
	$url='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
}
if(strripos($url,'/')){
	$domain = substr($url,0,strripos(substr($url,0,strripos($url,'/')),'/')+1);
}
$siteDomain = $domain;

// webim访问根目录
$imBaseUrl = "im/";
$baseUrl = $siteDomain.$imBaseUrl;

// 获取当前系统里存放用户id的session赋值给im的session_uid
$session_uid = $_SESSION[$session_prefix."mid"];

// 获取用户数据Sql
$getUserInfoSql = "SELECT uid uid,uname u_name,email u_email,CONCAT('data/uploads/avatar/',uid,'/small.jpg') u_ico FROM ts_user WHERE uid=$session_uid";
//开启(1)/关闭(0)同步主系统好友关系
$getMainFriend = 1;
//获取好友关系数据Sql
$getPalsListSql = "select u.uid uid,u.uname u_name,u.email u_email,CONCAT('data/uploads/avatar/',u.uid,'/small.jpg') u_ico from ts_weibo_follow f left join ts_user u on f.fid=u.uid where f.uid = $session_uid";

//// 好友管理链接
//$friendManageUrl = $siteDomain."modules.php?appuser_ico";
//$friendManageTarget = "imall_modules"; // _self, _blank, _parent, _top

//允许传输文件类型
$allowtype = array (
  0 => 'jpg',
  1 => 'gif',
  2 => 'png',
  3 => 'zip',
  4 => 'rar',
);
//允许传输文件的最大值，单位：KB
$allowmaxsize = "1024";
//传输文件临时存放目录，相对与im 目录下
$userfiledir = "uploadfiles/";
//文件在服务器上保存的天数
$savedays = 7;
	?>