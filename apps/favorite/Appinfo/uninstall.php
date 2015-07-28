<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	// group数据
	"DROP TABLE IF EXISTS `{$db_prefix}favorite`;",
	// ts_system_data数据
	"DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'favorite'",
	// 模板数据
	"DELETE FROM `{$db_prefix}template` WHERE `type` = 'favorite';",
	// 积分规则
	"DELETE FROM `{$db_prefix}credit_setting` WHERE `type` = 'favorite';",
);

foreach ($sql as $v)
	M('')->execute($v);