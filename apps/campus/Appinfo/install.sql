
DROP TABLE IF EXISTS `ts_campus`;
CREATE TABLE `ts_campus` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) default NULL,
  `place` varchar(40) default NULL,
  `info` text,
  `tags` text,
  `readCount` int(11) unsigned default '0',
  `savepath` varchar(255) default NULL,
  `size` int(11) NOT NULL default '0',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `cTime` int(11) unsigned default NULL,
  `mTime` int(11) unsigned default NULL,
  `isDel` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=978 DEFAULT CHARSET=utf8;

REPLACE INTO `ts_system_data` (`uid`,`list`,`key`,`value`,`mtime`)
VALUES
    (0, 'campus', 'version_number', 's:5:"36263";', '2012-11-07 01:56:31'),
    (0, 'campus', 'server', 's:0:"";', '2012-11-20 18:52:56'),
	(0, 'campus', 'credit', 's:5:\"score\";', '2010-12-24 11:22:17'),
    (0, 'campus', 'limitpage', 's:2:"10";', '2012-11-07 19:23:37');

#模板数据
DELETE FROM `ts_template` WHERE `name` = 'campus_create_weibo' OR `name` = 'campus_share_weibo';
INSERT INTO `ts_template` (`name`, `alias`, `title`, `body`, `lang`, `type`, `type2`, `is_cache`, `ctime`) 
VALUES
('campus_create_weibo', '上传资源', '', '我上传了一个资源:【{title}】 {url}', 'zh', 'campus', 'weibo', '0', '1372315210'),
('campus_share_weibo', '分享资源', '', '分享@{author} 的资源:【{title}】 {url}', 'zh', 'campus', 'weibo', '0', '1372315277');