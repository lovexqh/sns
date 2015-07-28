<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	// event数据
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource`;",
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource_file;",
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource_down;",
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource_score;",
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource_storage;",
// 	"DROP TABLE IF EXISTS `{$db_prefix}resource_type;",
	// ts_system_data数据
	"DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'campus'",
);

foreach ($sql as $v)
	M('')->execute($v);
?>