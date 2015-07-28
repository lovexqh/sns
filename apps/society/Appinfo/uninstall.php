<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	
	"DROP TABLE IF EXISTS `{$db_prefix}blog_link`;",
	"DROP TABLE IF EXISTS `{$db_prefix}photo_link`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_comment`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_member`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_message`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_news`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_share`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_visitor`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_vote`;",
	"DROP TABLE IF EXISTS `{$db_prefix}society_wall`;",
	
	"DELETE FROM `{$db_prefix}dsk_navbar` WHERE `navname` = '社交圈'",
);

foreach ($sql as $v)
	M('')->execute($v);