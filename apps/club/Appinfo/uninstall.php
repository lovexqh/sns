<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	
	"DROP TABLE IF EXISTS `{$db_prefix}club`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_account`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_account_item`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_document`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_dept`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_member`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_notice`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_reply`;",
	"DROP TABLE IF EXISTS `{$db_prefix}club_topic`;",
	
	"DELETE FROM `{$db_prefix}dsk_navbar` WHERE `navname` = '社团'",
);

foreach ($sql as $v)
	M('')->execute($v);