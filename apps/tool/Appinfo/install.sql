/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50091
Source Host           : localhost:3306
Source Database       : usn_dsk_db

Target Server Type    : MYSQL
Target Server Version : 50091
File Encoding         : 65001

Date: 2013-08-12 09:20:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ts_tool`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tool`;
CREATE TABLE `ts_tool` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键',
  `uid` int(11) NOT NULL COMMENT '上传用户',
  `title` char(255) NOT NULL COMMENT '工具名',
  `tags` varchar(255) default NULL,
  `class` int(11) default NULL COMMENT '大分类信息',
  `class_name` varchar(255) default NULL COMMENT '大类名称',
  `price` int(5) default NULL,
  `version` varchar(255) default NULL COMMENT '版本信息',
  `section` varchar(255) default NULL COMMENT '树下分类',
  `type` varchar(50) default NULL COMMENT '工具类型',
  `info` char(255) default NULL COMMENT '工具简介',
  `readCount` int(11) default '0' COMMENT '下载次数',
  `collectCount` int(11) default '0' COMMENT '查看量',
  `downCount` int(11) default '0' COMMENT '下载时间',
  `time` int(11) default NULL COMMENT '编辑时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_tool_down`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tool_down`;
CREATE TABLE `ts_tool_down` (
  `d_id` int(5) NOT NULL auto_increment,
  `uid` int(5) default NULL,
  `dTime` int(11) default NULL,
  `id` int(5) default NULL,
  PRIMARY KEY  (`d_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_tool_file`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tool_file`;
CREATE TABLE `ts_tool_file` (
  `f_id` int(5) NOT NULL auto_increment COMMENT '主键',
  `id` int(5) default NULL,
  `uid` int(11) default NULL COMMENT '用户',
  `saveaddress` varchar(255) default NULL COMMENT '保存地址',
  `savepath` varchar(255) default NULL COMMENT '路径',
  `savename` varchar(255) default NULL COMMENT '名称',
  `savetype` varchar(255) default NULL COMMENT '工具类型',
  `filecode` varchar(255) default NULL COMMENT '和服务器关联的字段',
  `pagecount` varchar(10) default NULL,
  `thumb` varchar(255) default NULL COMMENT '缩略图/图片路径',
  `toolsize` varchar(255) default NULL COMMENT '工具大小',
  `time` int(11) default NULL COMMENT '上传时间',
  PRIMARY KEY  (`f_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_tool_storage`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tool_storage`;
CREATE TABLE `ts_tool_storage` (
  `id` int(5) NOT NULL auto_increment,
  `address` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_tool_storage
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_tool_type`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tool_type`;
CREATE TABLE `ts_tool_type` (
  `id` int(5) NOT NULL auto_increment,
  `exttype` varchar(255) default NULL COMMENT '上传文件格式',
  `remark` varchar(255) default NULL COMMENT '简介',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;




REPLACE INTO `ts_system_data` (`uid`,`list`,`key`,`value`,`mtime`)
VALUES
    (0, 'tool', 'version_number', 's:5:"36263";', '2012-11-07 01:56:31'),
    (0, 'tool', 'server', 's:0:"";', '2012-11-20 18:52:56'),
	(0, 'tool', 'credit', 's:5:\"score\";', '2010-12-24 11:22:17'),
    (0, 'tool', 'limitpage', 's:2:"10";', '2012-11-07 19:23:37');

#模板数据
DELETE FROM `ts_template` WHERE `name` = 'tool_create_weibo' OR `name` = 'tool_share_weibo';
INSERT INTO `ts_template` (`name`, `alias`, `title`, `body`, `lang`, `type`, `type2`, `is_cache`, `ctime`) 
VALUES
('tool_create_weibo', '上传工具', '', '我上传了一个工具:【{title}】 {url}', 'zh', 'tool', 'weibo', '0', '1372315210'),
('tool_share_weibo', '分享工具', '', '分享@{author} 的工具:【{title}】 {url}', 'zh', 'tool', 'weibo', '0', '1372315277');



















