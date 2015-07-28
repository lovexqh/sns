SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ts_favorite`
-- ----------------------------
DROP TABLE IF EXISTS `ts_favorite`;
CREATE TABLE `ts_favorite` (
  `id` int(11) NOT NULL auto_increment COMMENT '自动增长值',
  `favuid` int(11) NOT NULL default '0' COMMENT '收藏用户的ID',
  `appname` varchar(20) NOT NULL COMMENT '对应的应用的名称',
  `action` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `appconfig` varchar(255) NOT NULL,
  `tablename` varchar(20) NOT NULL,
  `fid` int(11) NOT NULL default '0' COMMENT '收录的内容的ID',
  `addtime` int(11) NOT NULL COMMENT '收藏时间 ，如果是重复收藏，可以做更新，也可以不做',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
