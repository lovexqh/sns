<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	// event数据
	"DROP TABLE IF EXISTS `{$db_prefix}tool`;",
	"DROP TABLE IF EXISTS `{$db_prefix}tool_file;",
	"DROP TABLE IF EXISTS `{$db_prefix}tool_down;",
	"DROP TABLE IF EXISTS `{$db_prefix}tool_score;",
	"DROP TABLE IF EXISTS `{$db_prefix}tool_storage;",
	"DROP TABLE IF EXISTS `{$db_prefix}tool_type;",
	// ts_system_data数据
	"DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'tool'",
);

foreach ($sql as $v)
	M('')->execute($v);
?>