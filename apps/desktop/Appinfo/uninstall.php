<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

header('Content-Type: text/html; charset=utf-8');

$sql_file  = APPS_PATH.'/desktop/Appinfo/install.sql';
//执行sql文件
$res = M('')->executeSqlFile($sql_file);
if(!empty($res)){//错误
	echo $res['error_code'];
	echo '<br />';
	echo $res['error_sql'];
	exit;
}