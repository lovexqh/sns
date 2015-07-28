<?php
if(!$IWEB_IM_IN) {
	die('Hacking attempt');
}
$user = "root";
$pwd = "root";
$db = "esn_db";
$host = "localhost";

$dbServs=array($host,$db,$user,$pwd);
define('IM_DBTABLEPRE', 'chat_');
?>