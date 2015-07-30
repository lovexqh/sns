<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	// file数据
	"DROP TABLE IF EXISTS `{$db_prefix}file`;",
	"DROP TABLE IF EXISTS `{$db_prefix}file_small_type;",
	"DROP TABLE IF EXISTS `{$db_prefix}file_type;",
    "DROP TABLE IF EXISTS `{$db_prefix}file_widget;",
	// qbsns_system_data数据
	"DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'file'",
	// 模板数据
	"DELETE FROM `{$db_prefix}template` WHERE `type` = 'file';",
	// 积分规则
	"DELETE FROM `{$db_prefix}credit_setting` WHERE `type` = 'file';",
);

foreach ($sql as $v)
	M('')->execute($v);