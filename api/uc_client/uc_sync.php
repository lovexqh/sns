<?php
/*
 * ThinkSNS执行与UCenter的同步
 *
 * 本文件在TS核心框架中引入
 */
define('UC_SYNC', 1); // 0:关闭同步 1:开启同步

if(UC_SYNC) {
	include_once SITE_PATH.'/api/uc_client/uc_config.inc.php';
	include_once SITE_PATH.'/api/uc_client/client.php';
	
	include_once SITE_PATH.'/api/uc_client/service_config.inc.php';
	include_once SITE_PATH.'/api/uc_client/service.php';
	include_once SITE_PATH.'/api/uc_client/Service.class.php';
	include_once SITE_PATH.'/addons/libs/Http.class.php';
}
include_once SITE_PATH.'/api/uc_client/common.php';
?>