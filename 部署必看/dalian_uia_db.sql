/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50535
Source Host           : 127.0.0.1:3306
Source Database       : all_uia_db

Target Server Type    : MYSQL
Target Server Version : 50535
File Encoding         : 65001

Date: 2014-07-01 13:29:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for uc_academyinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_academyinfo`;
CREATE TABLE `uc_academyinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.院系id',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.院系所属学校id',
  `yxbm` varchar(20) DEFAULT NULL COMMENT '院系编码.学校自定义编码',
  `yxmc` varchar(50) DEFAULT NULL COMMENT '院系名称.',
  `yxywmc` varchar(60) DEFAULT NULL COMMENT '院系英文名称.',
  `yxjc` varchar(40) DEFAULT NULL COMMENT '院系简称.',
  `yxywjc` varchar(40) DEFAULT NULL COMMENT '院系英文简称.',
  `yxjp` varchar(20) DEFAULT NULL COMMENT '院系简拼.',
  `yxdz` varchar(50) DEFAULT NULL COMMENT '院系地址.',
  `lsdwh` varchar(20) DEFAULT NULL COMMENT '隶属单位号.',
  `jlny` varchar(10) DEFAULT NULL COMMENT '建立年月.',
  `yxlxr` varchar(20) DEFAULT NULL COMMENT '院系联系人.',
  `yxlxdh` varchar(20) DEFAULT NULL COMMENT '院系联系电话.',
  `yxlbm` char(2) DEFAULT NULL COMMENT '院系类别码.',
  `yxjj` varchar(200) DEFAULT NULL COMMENT '院系简介.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='院系信息表, 该表定义高等院校院系基本信息';

-- ----------------------------
-- Records of uc_academyinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_admins
-- ----------------------------
DROP TABLE IF EXISTS `uc_admins`;
CREATE TABLE `uc_admins` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `allowadminsetting` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminapp` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminuser` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminbadword` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmintag` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminpm` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincredits` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindomain` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindb` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminnote` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincache` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminlog` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_admins
-- ----------------------------

-- ----------------------------
-- Table structure for uc_app
-- ----------------------------
DROP TABLE IF EXISTS `uc_app`;
CREATE TABLE `uc_app` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_alias` varchar(255) NOT NULL,
  `description` text,
  `version` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:关闭 1:默认 2:可选',
  `category` varchar(255) DEFAULT NULL,
  `release_date` varchar(255) DEFAULT NULL,
  `last_update_date` varchar(255) DEFAULT NULL,
  `host_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：本地应用 1：远程应用',
  `app_entry` varchar(255) DEFAULT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `large_icon_url` varchar(255) DEFAULT NULL,
  `admin_entry` varchar(255) DEFAULT NULL,
  `statistics_entry` varchar(255) DEFAULT NULL,
  `homepage_url` varchar(255) DEFAULT NULL,
  `sidebar_title` varchar(255) DEFAULT NULL,
  `sidebar_entry` varchar(255) DEFAULT NULL,
  `sidebar_icon_url` varchar(255) DEFAULT NULL,
  `sidebar_support_submenu` tinyint(1) NOT NULL DEFAULT '0',
  `sidebar_is_submenu_active` tinyint(1) NOT NULL DEFAULT '0',
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `author_homepage_url` varchar(255) DEFAULT NULL,
  `contributor_name` text,
  `display_order` smallint(5) NOT NULL DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  PRIMARY KEY (`app_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_app
-- ----------------------------
INSERT INTO `uc_app` VALUES ('1', '0', 'society', '社交圈', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('3', '0', 'photo', '我的相册', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('4', '0', 'event', '我的活动', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('5', '0', 'vote', '我的投票', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('6', '0', 'timeline', '时光轴', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('7', '0', 'gift', '我的礼物', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('9', '0', 'desktop', 'Web桌面', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('11', '0', 'favorite', '我的收藏', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('14', '0', 'tool', '应用工具', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('21', '0', 'club', '社团', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('25', '0', 'tool_square', '工具广场', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('29', '0', 'article_square', '文章广场', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('31', '0', 'poster', '我的招贴', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);
INSERT INTO `uc_app` VALUES ('32', '0', 'blog', '我的博客', '', null, '1', null, null, '2014-06-25', '0', null, null, null, null, null, null, null, null, null, '0', '0', null, null, null, null, '0', null);

-- ----------------------------
-- Table structure for uc_applications
-- ----------------------------
DROP TABLE IF EXISTS `uc_applications`;
CREATE TABLE `uc_applications` (
  `appid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `authkey` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `viewprourl` varchar(255) NOT NULL,
  `apifilename` varchar(30) NOT NULL DEFAULT 'uc.php',
  `charset` varchar(8) NOT NULL DEFAULT '',
  `dbcharset` varchar(8) NOT NULL DEFAULT '',
  `synlogin` tinyint(1) NOT NULL DEFAULT '0',
  `recvnote` tinyint(1) DEFAULT '0',
  `extra` text NOT NULL,
  `tagtemplates` text NOT NULL,
  `allowips` text NOT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_applications
-- ----------------------------
INSERT INTO `uc_applications` VALUES ('1', 'OTHER', '阳光教育社区', 'http://edns.ruijie-grid.com', '8d4cZM6/vYHT4Krz0QuYfWQmXuu9gjmu8lMeYFhOe0nQsy4AqKr2liEQInKZNjMRrAfx+UdQ4SeFFZc5mbWRAKDLDzQ', '172.16.172.235', '', 'uc.php', '', '', '1', '1', 'a:2:{s:7:\"apppath\";s:0:\"\";s:8:\"extraurl\";a:0:{}}', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"template\"><![CDATA[]]></item>\r\n</root>', '');

-- ----------------------------
-- Table structure for uc_badwords
-- ----------------------------
DROP TABLE IF EXISTS `uc_badwords`;
CREATE TABLE `uc_badwords` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `admin` varchar(15) NOT NULL DEFAULT '',
  `find` varchar(255) NOT NULL DEFAULT '',
  `replacement` varchar(255) NOT NULL DEFAULT '',
  `findpattern` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `find` (`find`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_badwords
-- ----------------------------

-- ----------------------------
-- Table structure for uc_classinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_classinfo`;
CREATE TABLE `uc_classinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.班级所属学校id',
  `yxid` int(11) DEFAULT NULL COMMENT '院系id.班级所属院系id',
  `yxbm` varchar(20) DEFAULT NULL COMMENT '院系编码.该处为冗余设计，为导入数据，同步等使用',
  `bh` varchar(20) DEFAULT NULL COMMENT '班号.',
  `bm` varchar(40) DEFAULT NULL COMMENT '班名.',
  `jbny` varchar(10) DEFAULT NULL COMMENT '建班时间.',
  `ssnj` varchar(10) DEFAULT NULL COMMENT '所属年级.',
  `zyid` int(11) DEFAULT NULL,
  `zyh` varchar(20) DEFAULT NULL COMMENT '专业号.',
  `ywbm` varchar(40) DEFAULT NULL COMMENT '英文班名.',
  `bjlx` varchar(10) DEFAULT NULL COMMENT '班级类型.01本科，02研究生，03专科',
  `bjlxm` char(2) DEFAULT NULL,
  `xz` varchar(10) DEFAULT NULL COMMENT '学制.',
  `xqdm` varchar(10) DEFAULT NULL COMMENT '校区代码.',
  `kcsflb` varchar(10) DEFAULT NULL COMMENT 'KCSFLB.？？',
  `bzrid` int(11) DEFAULT NULL COMMENT '班主任id.',
  `bzid` int(11) DEFAULT NULL COMMENT '班长id.',
  `fdyid` int(11) DEFAULT NULL COMMENT '辅导员id.',
  `sfddb` char(1) DEFAULT NULL COMMENT '是否订单班.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='班级信息表, 该表定义高校班级的基本信息';

-- ----------------------------
-- Records of uc_classinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_classinfo_copy
-- ----------------------------
DROP TABLE IF EXISTS `uc_classinfo_copy`;
CREATE TABLE `uc_classinfo_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xxid` int(11) NOT NULL,
  `bh` varchar(50) NOT NULL COMMENT '班号',
  `bj` varchar(50) NOT NULL COMMENT '班级',
  `xd` int(11) NOT NULL COMMENT '级部id',
  `jbny` int(11) NOT NULL COMMENT '建班年月',
  `bzrgh` varchar(50) NOT NULL COMMENT '班主任工号',
  `bzxh` varchar(50) NOT NULL COMMENT '班长学号',
  `bjrych` varchar(100) NOT NULL COMMENT '班级荣誉称号',
  `xz` int(1) NOT NULL COMMENT '学制',
  `bjlxm` varchar(50) NOT NULL COMMENT '班级类型码',
  `wllx` int(1) NOT NULL COMMENT '文理类型',
  `byrq` int(11) NOT NULL COMMENT '毕业日期',
  `sfssmzsy` int(1) NOT NULL COMMENT '是否少数民族双语教学班',
  `syjxmsm` varchar(50) NOT NULL COMMENT '双语教学模式码',
  `isdel` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_classinfo_copy
-- ----------------------------

-- ----------------------------
-- Table structure for uc_classroom
-- ----------------------------
DROP TABLE IF EXISTS `uc_classroom`;
CREATE TABLE `uc_classroom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.教室id',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.教室所属学校id',
  `xqh` varchar(10) DEFAULT NULL COMMENT '校区号.',
  `jxlid` int(11) DEFAULT NULL COMMENT '教学楼id.',
  `jxlh` varchar(20) DEFAULT NULL COMMENT '教学楼号.该处为冗余设计，为导入数据，同步等使用',
  `jxlm` varchar(20) DEFAULT NULL COMMENT '教学楼名.该处为冗余设计，为导入数据，同步等使用',
  `jsh` varchar(20) DEFAULT NULL COMMENT '教室号.',
  `jsm` varchar(20) DEFAULT NULL COMMENT '教室名.',
  `szlc` int(4) DEFAULT NULL COMMENT '所在楼层.',
  `jsyt` varchar(30) DEFAULT NULL COMMENT '教室用途.',
  `zws` int(4) DEFAULT NULL COMMENT '座位数.',
  `yxzws` int(4) DEFAULT NULL COMMENT '有效座位数.',
  `kszws` int(4) DEFAULT NULL COMMENT '考试座位数.',
  `jslx` varchar(20) DEFAULT NULL COMMENT '教室类型.该处为冗余设计，为导入数据，同步等使用',
  `jslxm` char(2) DEFAULT NULL COMMENT '教室类型码.',
  `jsms` varchar(40) DEFAULT NULL COMMENT '教室描述.',
  `jsglbm` varchar(40) DEFAULT NULL COMMENT '教室管理部门.',
  `xnd` varchar(20) DEFAULT NULL COMMENT '学年度.',
  `kkxq` varchar(20) DEFAULT NULL COMMENT '开课学期.',
  `ppjslx` varchar(30) DEFAULT NULL COMMENT '匹配教室类型.',
  `mzjslx` varchar(30) DEFAULT NULL COMMENT '满足教室类型.',
  `yxj` varchar(10) DEFAULT NULL COMMENT '优先级.',
  `bz` varchar(20) DEFAULT NULL COMMENT '备注.',
  `sortOrder` int(3) DEFAULT NULL COMMENT '排序号.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  `skzws` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jxlh` (`jxlh`,`jxlm`,`jsh`,`jsm`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='教室信息表, 该表定义学校的教室信息';

-- ----------------------------
-- Records of uc_classroom
-- ----------------------------

-- ----------------------------
-- Table structure for uc_course
-- ----------------------------
DROP TABLE IF EXISTS `uc_course`;
CREATE TABLE `uc_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.课程id',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.班级所属学校id',
  `yxid` int(11) DEFAULT NULL COMMENT '院系id.班级所属院系id',
  `yxbm` varchar(20) DEFAULT NULL COMMENT '院系编码.该处为冗余设计，为导入数据，同步等使用',
  `kch` varchar(20) DEFAULT NULL COMMENT '课程号.学校自编，该处为冗余设计，为导入数据，同步等使用',
  `kcm` varchar(40) DEFAULT NULL COMMENT '课程名.',
  `kxh` varchar(10) DEFAULT NULL COMMENT '课序号.学校自编',
  `jxbjid` int(11) DEFAULT NULL COMMENT '教学班级id.',
  `jxbh` varchar(20) DEFAULT NULL COMMENT '教学班号.该处为冗余设计，为导入数据，同步等使用',
  `jxbjmc` varchar(40) DEFAULT NULL COMMENT '教学班级名称.该处为冗余设计，为导入数据，同步等使用',
  `kcksdwid` int(11) DEFAULT NULL COMMENT '课程开设单位id.开课院系，系所id',
  `kcksdwmc` varchar(40) DEFAULT NULL COMMENT '课程开设单位名称.该处为冗余设计，为导入数据，同步等使用',
  `kcksdwbm` varchar(40) DEFAULT NULL COMMENT '课程开设单位编码.该处为冗余设计，为导入数据，同步等使用',
  `kkxnd` varchar(20) DEFAULT NULL COMMENT '开课学年度.上学期，下学期，或春，秋。该处为冗余设计，为导入数据，同步等使用',
  `kkxqm` char(2) DEFAULT NULL COMMENT '开课学期码.上学期，下学期，或春，秋。',
  `kkxq` varchar(10) DEFAULT NULL COMMENT '开课学期.该处为冗余设计，为导入数据，同步等使用',
  `sksj` varchar(30) DEFAULT NULL COMMENT '上课时间.可以字符分割形式表示',
  `jxdd` varchar(40) DEFAULT NULL COMMENT '教学地点.教学活动安排地点',
  `jxlid` int(11) DEFAULT NULL COMMENT '教学楼id.',
  `jxlh` varchar(20) DEFAULT NULL COMMENT '教学楼号.该处为冗余设计，为导入数据，同步等使用',
  `jxlm` varchar(40) DEFAULT NULL COMMENT '教学楼名.该处为冗余设计，为导入数据，同步等使用',
  `jshiid` int(11) DEFAULT NULL COMMENT '教室id.',
  `jsbm` varchar(20) DEFAULT NULL COMMENT '教室编码.该处为冗余设计，为导入数据，同步等使用',
  `jsm` varchar(40) DEFAULT NULL COMMENT '教室名.该处为冗余设计，为导入数据，同步等使用',
  `jslx` varchar(10) DEFAULT NULL COMMENT '教室类型.该处为冗余设计，为导入数据，同步等使用',
  `jslxm` char(2) DEFAULT NULL COMMENT '教室类型码.如电教室，语音，视频教室等',
  `mzjslx` varchar(30) DEFAULT NULL COMMENT '满足教室类型.',
  `jxzy` varchar(40) DEFAULT NULL COMMENT '教学资源.教学的辅助工具、设备等资源',
  `krl` int(5) DEFAULT NULL COMMENT '课容量.容纳学生数，单位：人',
  `xdrs` int(4) DEFAULT NULL COMMENT '修读人数.修读学生数，单位：人',
  `jszws` int(4) DEFAULT NULL COMMENT '教室座位数.',
  `xkxqh` varchar(10) DEFAULT NULL COMMENT '选课校区号.',
  `xkrsxd` int(4) DEFAULT NULL COMMENT '选课人数限定.单位：人',
  `xknj` varchar(10) DEFAULT NULL COMMENT '选课年级.',
  `pkyq` varchar(100) DEFAULT NULL COMMENT '排课要求.',
  `skqsz` int(2) DEFAULT NULL COMMENT '上课起始周.',
  `skzzz` int(2) DEFAULT NULL COMMENT '上课终止周.',
  `skxq` int(2) DEFAULT NULL COMMENT '上课星期.0表示周日，1表示周一，2表示周二，依次类推',
  `skjc` int(2) DEFAULT NULL COMMENT '上课节次.表示第几课',
  `kczxs` float DEFAULT NULL COMMENT '课程总学时.',
  `sjzxs` float DEFAULT NULL COMMENT '上机总学时.',
  `syzxs` float DEFAULT NULL COMMENT '实验总学时.',
  `dsz` char(1) DEFAULT NULL COMMENT '单双周.0表示每周，1表示单周，2表示双周，3表示从第一周开始的部分周，4表示不从第一周开始，不到最后一周结束的部分周，5表示到最后一周结束的部分周。具体上课周次需要与“学时状况”字段配合决定',
  `xszk` varchar(30) DEFAULT NULL COMMENT '学时状况.通过字符如3333333333333300000的形式决定上课周次及学时。具体是否上课需要与单双周配合决定',
  `xf` float DEFAULT NULL COMMENT '学分.',
  `jsid` int(11) DEFAULT NULL COMMENT '教师id.授课教师工号，学校自编',
  `jsh` varchar(20) DEFAULT NULL COMMENT '教师号1.该处为冗余设计，为导入数据，同步等使用',
  `jsxm` varchar(20) DEFAULT NULL COMMENT '教师姓名.',
  `jsid2` int(11) DEFAULT NULL COMMENT '教师2id.',
  `jsh2` varchar(20) DEFAULT NULL COMMENT '教师号2.该处为冗余设计，为导入数据，同步等使用',
  `jsxm2` varchar(20) DEFAULT NULL COMMENT '教师2姓名.',
  `kcxz` varchar(10) DEFAULT NULL COMMENT '课程性质.必修，选修，必选等。该处为冗余设计，为导入数据，同步等使用',
  `kcxzm` char(2) DEFAULT NULL COMMENT '课程性质码.',
  `zxs` float DEFAULT NULL COMMENT '周学时.',
  `kslx` varchar(10) DEFAULT NULL COMMENT '考试类型.该处为冗余设计，为导入数据，同步等使用',
  `kslxm` char(2) DEFAULT NULL COMMENT '考试类型码.',
  `kccc` varchar(10) DEFAULT NULL COMMENT '课程层次.课程类别层次：本科，专科，研究生等。该处为冗余设计，为导入数据，同步等使用',
  `kcccm` char(2) DEFAULT NULL COMMENT '课程层次码.本科，专科，研究生等',
  `kksm` varchar(100) DEFAULT NULL COMMENT '开课说明.',
  `pxh` int(4) DEFAULT NULL COMMENT '排序号.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`),
  KEY `kch` (`kch`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程表, 该表定义高校课程表';

-- ----------------------------
-- Records of uc_course
-- ----------------------------

-- ----------------------------
-- Table structure for uc_courseselected
-- ----------------------------
DROP TABLE IF EXISTS `uc_courseselected`;
CREATE TABLE `uc_courseselected` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `kcid` int(11) DEFAULT NULL COMMENT '课程id.课程id，对应课程表中的课程id',
  `kch` varchar(20) DEFAULT NULL COMMENT '课程号.该处为冗余设计，为导入数据，同步等使用',
  `kxh` varchar(10) DEFAULT NULL COMMENT '课序号.',
  `xsid` int(11) DEFAULT NULL COMMENT '学生id.',
  `xh` varchar(20) DEFAULT NULL COMMENT '学号.该处为冗余设计，为导入数据，同步等使用',
  `xksx` varchar(20) DEFAULT NULL COMMENT '选课属性.',
  `xkkz` varchar(20) DEFAULT NULL COMMENT '选课课组.',
  `cqbz` varchar(10) DEFAULT NULL COMMENT '抽签标志.该处为冗余设计，为导入数据，同步等使用',
  `cqbzm` char(2) DEFAULT NULL COMMENT '抽签标志码.',
  `zqbz` varchar(10) DEFAULT NULL COMMENT '中签标志.该处为冗余设计，为导入数据，同步等使用',
  `zqbzm` char(2) DEFAULT NULL COMMENT '中签标志码.',
  `cxbkbz` varchar(10) DEFAULT NULL COMMENT '重修补考标志.该处为冗余设计，为导入数据，同步等使用',
  `cxbkbzm` char(2) DEFAULT NULL COMMENT '重修补考标志码.',
  `bz` varchar(20) DEFAULT NULL COMMENT '备注.',
  `xksj` varchar(10) DEFAULT NULL COMMENT '选课时间.',
  `xklcm` char(2) DEFAULT NULL COMMENT '选课轮次码.',
  `yxj` char(2) DEFAULT NULL COMMENT '优先级.',
  `lrbz` char(2) DEFAULT NULL COMMENT '录入标志.',
  `xklx` char(2) DEFAULT NULL COMMENT '选课类型.',
  `tdkch` varchar(20) DEFAULT NULL COMMENT '替代课程号.',
  `njyq` varchar(40) DEFAULT NULL COMMENT '年级要求.',
  `yxyq` varchar(40) DEFAULT NULL COMMENT '院系要求.',
  `zyyq` varchar(40) DEFAULT NULL COMMENT '专业要求.',
  `xqyq` varchar(40) DEFAULT NULL COMMENT '校区要求.',
  `xkzy` varchar(40) DEFAULT NULL COMMENT '选课志愿.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`),
  KEY `kch` (`kch`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='选课信息表, 该表定义学生的选课信息';

-- ----------------------------
-- Records of uc_courseselected
-- ----------------------------

-- ----------------------------
-- Table structure for uc_dept
-- ----------------------------
DROP TABLE IF EXISTS `uc_dept`;
CREATE TABLE `uc_dept` (
  `deptID` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `schoolid` int(11) DEFAULT NULL COMMENT '学校id.部门所属学校id',
  `UpDeptID` int(11) DEFAULT NULL COMMENT '上级部门id.',
  `deptCode` varchar(20) DEFAULT NULL COMMENT '部门编码.',
  `departName` varchar(50) DEFAULT NULL COMMENT '部门名称.',
  `deptNameEN` varchar(60) DEFAULT NULL COMMENT '部门英文名称.',
  `deptShortName` varchar(40) DEFAULT NULL COMMENT '部门简称.',
  `deptShortNameEN` varchar(40) DEFAULT NULL COMMENT '部门英文简称.',
  `deptAddress` varchar(50) DEFAULT NULL COMMENT '部门地址.',
  `DeptManager` varchar(20) DEFAULT NULL COMMENT '部门联系人.',
  `DeptPhone` varchar(20) DEFAULT NULL COMMENT '部门联系电话.',
  `DeptComment` varchar(200) DEFAULT NULL COMMENT '部门简介.',
  `deptType` varchar(10) DEFAULT NULL COMMENT '部门类别码.',
  `deptFlag` char(2) DEFAULT NULL COMMENT '部门有效标识.部门是否有效',
  `invalidDate` varchar(10) DEFAULT NULL COMMENT '失效日期.',
  `sortOrder` int(4) DEFAULT NULL COMMENT '排序号.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`deptID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='部门信息表, 该表定义高等院校部门基本信息';

-- ----------------------------
-- Records of uc_dept
-- ----------------------------

-- ----------------------------
-- Table structure for uc_dept_type
-- ----------------------------
DROP TABLE IF EXISTS `uc_dept_type`;
CREATE TABLE `uc_dept_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_dept_type
-- ----------------------------

-- ----------------------------
-- Table structure for uc_dict_item
-- ----------------------------
DROP TABLE IF EXISTS `uc_dict_item`;
CREATE TABLE `uc_dict_item` (
  `autoid` int(11) NOT NULL AUTO_INCREMENT,
  `dataid` char(10) NOT NULL,
  `itemcode` varchar(5) NOT NULL,
  `itemcn` varchar(50) NOT NULL,
  `itemen` varchar(10) NOT NULL,
  `itempid` varchar(5) NOT NULL DEFAULT '0',
  `sorder` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`autoid`),
  KEY `dataid` (`dataid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3208 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_dict_item
-- ----------------------------
INSERT INTO `uc_dict_item` VALUES ('1', 'jg00000001', '2', '初等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2', 'jg00000001', '3', '中等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3', 'jg00000001', '4', '高等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('4', 'jg00000001', '5', '特殊教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('5', 'jg00000001', '9', '其他教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('6', 'jg00000001', '11', '幼儿园', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('7', 'jg00000001', '21', '小学', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('8', 'jg00000001', '22', '成人小学', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('9', 'jg00000001', '31', '普通初中', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('10', 'jg00000001', '32', '职业初中', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('11', 'jg00000001', '33', '成人初中', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('12', 'jg00000001', '34', '普通高中', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('13', 'jg00000001', '35', '成人高中', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('14', 'jg00000001', '36', '中等职业学校', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('15', 'jg00000001', '37', '工读学校', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('16', 'jg00000001', '41', '普通高等学校', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('17', 'jg00000001', '42', '成人高等学校', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('18', 'jg00000001', '51', '特殊教育学校', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('19', 'jg00000001', '91', '培养研究生的科研机构', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('20', 'jg00000001', '92', '民办的其他高等教育机构', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('21', 'jg00000001', '93', '中等职业培训机构', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('22', 'jg00000001', '111', '幼儿园', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('23', 'jg00000001', '119', '附设幼儿班', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('24', 'jg00000001', '211', '小学', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('25', 'jg00000001', '218', '小学教学点', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('26', 'jg00000001', '219', '附设小学班', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('27', 'jg00000001', '221', '职工小学', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('28', 'jg00000001', '222', '农民小学', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('29', 'jg00000001', '228', '小学班', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('30', 'jg00000001', '229', '扫盲班', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('31', 'jg00000001', '311', '初级中学', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('32', 'jg00000001', '312', '九年一贯制学校', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('33', 'jg00000001', '319', '附设普通初中班', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('34', 'jg00000001', '321', '职业初中', '', '', '32', '');
INSERT INTO `uc_dict_item` VALUES ('35', 'jg00000001', '329', '附设职业初中班', '', '', '32', '');
INSERT INTO `uc_dict_item` VALUES ('36', 'jg00000001', '331', '成人职工初中', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('37', 'jg00000001', '332', '成人农民初中', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('38', 'jg00000001', '341', '完全中学', '', '', '34', '');
INSERT INTO `uc_dict_item` VALUES ('39', 'jg00000001', '342', '高级中学', '', '', '34', '');
INSERT INTO `uc_dict_item` VALUES ('40', 'jg00000001', '345', '十二年一贯制学校', '', '', '34', '');
INSERT INTO `uc_dict_item` VALUES ('41', 'jg00000001', '349', '附设普通高中班', '', '', '34', '');
INSERT INTO `uc_dict_item` VALUES ('42', 'jg00000001', '351', '成人职工高中', '', '', '35', '');
INSERT INTO `uc_dict_item` VALUES ('43', 'jg00000001', '352', '成人农民高中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('44', 'jg00000001', '361', '调整后中等职业学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('45', 'jg00000001', '362', '中等技术学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('46', 'jg00000001', '363', '中等师范学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('47', 'jg00000001', '364', '成人中等专业学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('48', 'jg00000001', '365', '职业高中学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('49', 'jg00000001', '366', '技工学校', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('50', 'jg00000001', '368', '附设中职班', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('51', 'jg00000001', '369', '其他机构', '', '', '36', '');
INSERT INTO `uc_dict_item` VALUES ('52', 'jg00000001', '371', '工读学校', '', '', '37', '');
INSERT INTO `uc_dict_item` VALUES ('53', 'jg00000001', '411', '本科院校：大学', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('54', 'jg00000001', '412', '本科院校：学院', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('55', 'jg00000001', '413', '本科院校：独立学院', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('56', 'jg00000001', '414', '专科院校：高等专科学校', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('57', 'jg00000001', '415', '专科院校：高等职业学校', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('58', 'jg00000001', '419', '其他机构：分校、大专班', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('59', 'jg00000001', '421', '职工高校', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('60', 'jg00000001', '422', '农民高校', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('61', 'jg00000001', '423', '管理干部学院', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('62', 'jg00000001', '424', '教育学院', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('63', 'jg00000001', '425', '独立函授学院', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('64', 'jg00000001', '426', '广播电视大学', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('65', 'jg00000001', '429', '其他成人高等教育机构', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('66', 'jg00000001', '511', '盲人学校', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('67', 'jg00000001', '512', '聋人学校', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('68', 'jg00000001', '513', '弱智学校', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('69', 'jg00000001', '514', '其他特殊教育学校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('70', 'jg00000001', '519', '附设特教班', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('71', 'jg00000001', '911', '培养研究生的科研机构', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('72', 'jg00000001', '921', '民办的其他高等教育机构', '', '', '92', '');
INSERT INTO `uc_dict_item` VALUES ('73', 'jg00000001', '931', '职工技术培训学校（机构）93', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('74', 'jg00000001', '932', '农村成人文化技术培训学校（机构）93', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('75', 'jg00000001', '933', '其他培训机构（含社会培训机构）93', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('76', 'jg00000002', '1', '直属（校办）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('77', 'jg00000002', '2', '中外合作办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('78', 'jg00000002', '3', '校企合办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('79', 'jg00000002', '4', '民办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('80', 'jg00000002', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('81', 'jg00000003', '1', '教学院系', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('82', 'jg00000003', '2', '科研机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('83', 'jg00000003', '3', '公共服务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('84', 'jg00000003', '4', '党务部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('85', 'jg00000003', '5', '行政机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('86', 'jg00000003', '6', '附属单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('87', 'jg00000003', '7', '后勤部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('88', 'jg00000003', '8', '校办产业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('89', 'jg00000003', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('90', 'jg00000004', '1', '城镇', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('91', 'jg00000004', '2', '乡村', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('92', 'jg00000004', '11', '城区', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('93', 'jg00000004', '12', '镇区', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('94', 'jg00000004', '111', '主城区', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('95', 'jg00000004', '112', '城乡结合区', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('96', 'jg00000004', '121', '镇中心区', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('97', 'jg00000004', '122', '镇乡结合区', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('98', 'jg00000004', '123', '特殊区域', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('99', 'jg00000004', '210', '乡中心区', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('100', 'jg00000004', '220', '村庄', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('101', 'jg00000005', '0', '非贫困县', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('102', 'jg00000005', '1', '国家级贫困县', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('103', 'jg00000005', '2', '省级贫困县', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('104', 'jg00000006', '10', '普通中小学/职业中学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('105', 'jg00000006', '11', '教育部门和集体办', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('106', 'jg00000006', '12', '社会力量办', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('107', 'jg00000006', '19', '其他部门办', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('108', 'jg00000006', '20', '其他中等职业学校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('109', 'jg00000006', '21', '公办', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('110', 'jg00000006', '22', '民办', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('111', 'jg00000006', '23', '民办公助', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('112', 'jg00000006', '29', '其他', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('113', 'jg00000007', '1', '新设', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('114', 'jg00000007', '2', '更名', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('115', 'jg00000007', '3', '合并', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('116', 'jg00000007', '4', '撤消', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('117', 'jg00000007', '5', '转置', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('118', 'jg00000007', '6', '升格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('119', 'jg00000008', '1', '校本部', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('120', 'jg00000008', '2', '分校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('121', 'jg00000008', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('122', 'jg00000009', '1', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('123', 'jg00000009', '2', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('124', 'jg00000009', '3', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('125', 'jg00000009', '4', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('126', 'jg00000009', '5', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('127', 'jg00000009', '6', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('128', 'jg00000009', '7', '中央党政机关、人民团体及其他机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('129', 'jg00000009', '8', '省与省级以下地方部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('130', 'jg00000009', '9', '省与省级以下地方部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('131', 'jg00000009', '811', '省级教育部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('132', 'jg00000009', '812', '省级其他部门（党政机关）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('133', 'jg00000009', '821', '地级教育部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('134', 'jg00000009', '822', '地级其他部门（党政机关）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('135', 'jg00000009', '831', '县级教育部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('136', 'jg00000009', '832', '县级其他部门（党政机关）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('137', 'jg00000009', '891', '地方企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('138', 'jg00000009', '892', '事业单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('139', 'jg00000009', '893', '部队', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('140', 'jg00000009', '894', '集体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('141', 'jg00000009', '999', '民办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('142', 'jg00000010', '01', '综合大学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('143', 'jg00000010', '02', '理工院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('144', 'jg00000010', '03', '农业院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('145', 'jg00000010', '04', '林业院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('146', 'jg00000010', '05', '医药院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('147', 'jg00000010', '06', '师范院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('148', 'jg00000010', '07', '语文院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('149', 'jg00000010', '08', '财经院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('150', 'jg00000010', '09', '政法院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('151', 'jg00000010', '10', '体育院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('152', 'jg00000010', '11', '艺术院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('153', 'jg00000010', '12', '民族院校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('154', 'jg00000011', '1', '课堂教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('155', 'jg00000011', '2', '专题报告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('156', 'jg00000011', '3', '应急演练', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('157', 'jg00000011', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('158', 'jg00000012', '10', '升学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('159', 'jg00000012', '11', '本科', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('160', 'jg00000012', '12', '高职（专科）', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('161', 'jg00000012', '13', '职业高中（含中专、技校）', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('162', 'jg00000012', '14', '职业初中', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('163', 'jg00000012', '15', '普通高中', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('164', 'jg00000012', '16', '普通初中', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('165', 'jg00000012', '20', '就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('166', 'jg00000012', '30', '参军', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('167', 'jg00000012', '40', '出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('168', 'jg00000012', '50', '赴港澳台', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('169', 'jg00000012', '60', '待就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('170', 'jg00000012', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('171', 'jg00000013', '1', '警告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('172', 'jg00000013', '2', '严重警告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('173', 'jg00000013', '3', '记过', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('174', 'jg00000013', '4', '留校察看', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('175', 'jg00000013', '6', '开除学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('176', 'jg00000013', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('177', 'jg00000014', '1', '视力残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('178', 'jg00000014', '2', '听力残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('179', 'jg00000014', '3', '智力残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('180', 'jg00000014', '9', '其他残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('181', 'jg00000015', '1', '少数民族', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('182', 'jg00000015', '2', '竞赛获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('183', 'jg00000015', '3', '文艺特长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('184', 'jg00000015', '4', '美术特长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('185', 'jg00000015', '5', '体育特长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('186', 'jg00000015', '6', '品学兼优', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('187', 'jg00000015', '9', '其他自主认定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('188', 'jg00000016', '1', '直硕', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('189', 'jg00000016', '2', '直博', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('190', 'jg00000016', '3', '直硕博', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('191', 'jg00000016', '4', '外推读研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('192', 'jg00000016', '5', '保留研究生学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('193', 'jg00000016', '6', '出国（境）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('194', 'jg00000016', '7', '就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('195', 'jg00000016', '8', '待就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('196', 'jg00000016', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('197', 'jg00000017', '11', '公开招考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('198', 'jg00000017', '22', '提前攻博', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('199', 'jg00000017', '23', '硕博连读', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('200', 'jg00000017', '24', '本科直博', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('201', 'jg00000017', '35', '本博连读（八年制临床医学博士）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('202', 'jg00000017', '46', '同等学力申请', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('203', 'jg00000017', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('204', 'jg00000018', '01', '语文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('205', 'jg00000018', '02', '数学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('206', 'jg00000018', '03', '外语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('207', 'jg00000018', '04', '物理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('208', 'jg00000018', '05', '化学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('209', 'jg00000018', '06', '生物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('210', 'jg00000018', '07', '政治', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('211', 'jg00000018', '08', '历史', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('212', 'jg00000018', '09', '地理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('213', 'jg00000018', '10', '综合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('214', 'jg00000018', '11', '文科综合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('215', 'jg00000018', '12', '理科综合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('216', 'jg00000018', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('217', 'jg00000019', '10', '派遣', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('218', 'jg00000019', '20', '用人单位接收', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('219', 'jg00000019', '30', '升学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('220', 'jg00000019', '40', '出国（境）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('221', 'jg00000019', '50', '暂缓就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('222', 'jg00000019', '60', '灵活就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('223', 'jg00000019', '70', '定向委培', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('224', 'jg00000019', '80', '国家地方项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('225', 'jg00000019', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('226', 'jg00000020', '1', '科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('227', 'jg00000020', '2', '教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('228', 'jg00000020', '3', '设计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('229', 'jg00000020', '4', '管理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('230', 'jg00000020', '5', '生产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('231', 'jg00000020', '6', '行政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('232', 'jg00000020', '7', '后勤', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('233', 'jg00000020', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('234', 'jg00000021', '1', '直接授予', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('235', 'jg00000021', '2', '参加学历文凭考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('236', 'jg00000021', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('237', 'jg00000022', '1', '集体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('238', 'jg00000022', '2', '个人综合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('239', 'jg00000022', '3', '个人单项', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('240', 'jg00000023', '1', '待领', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('241', 'jg00000023', '2', '已领', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('242', 'jg00000023', '3', '缓办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('243', 'jg00000023', '4', '户口不在校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('244', 'jg00000024', '1', '走读', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('245', 'jg00000024', '2', '住校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('246', 'jg00000024', '3', '借宿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('247', 'jg00000024', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('248', 'jg00000025', '1', '奖状', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('249', 'jg00000025', '2', '荣誉称号', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('250', 'jg00000025', '3', '奖金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('251', 'jg00000025', '4', '实物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('252', 'jg00000025', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('253', 'jg00000026', '1', '中央财政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('254', 'jg00000026', '2', '省级财政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('255', 'jg00000026', '3', '市级财政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('256', 'jg00000026', '4', '县级财政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('257', 'jg00000026', '5', '学校事业收入提取', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('258', 'jg00000026', '6', '企事业单位、社会或个人捐赠', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('259', 'jg00000026', '7', '金融机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('260', 'jg00000026', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('261', 'jg00000027', '1', '未缴', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('262', 'jg00000027', '2', '已缴清', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('263', 'jg00000027', '3', '免缴', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('264', 'jg00000027', '4', '部分缴纳', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('265', 'jg00000028', '1', '国际级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('266', 'jg00000028', '2', '国家级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('267', 'jg00000028', '3', '省（区、市）部级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('268', 'jg00000028', '4', '校级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('269', 'jg00000028', '5', '行业或企业级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('270', 'jg00000028', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('271', 'jg00000029', '1', '双亲健全', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('272', 'jg00000029', '2', '孤儿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('273', 'jg00000029', '3', '单亲（父母一方去世）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('274', 'jg00000029', '4', '父母离异', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('275', 'jg00000029', '5', '双亲有残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('276', 'jg00000029', '6', '本人残疾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('277', 'jg00000029', '7', '军烈属或优抚对象', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('278', 'jg00000029', '8', '重病', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('279', 'jg00000029', '9', '五保户', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('280', 'jg00000030', '01', '国家奖助类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('281', 'jg00000030', '02', '综合优秀类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('282', 'jg00000030', '03', '学业优秀类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('283', 'jg00000030', '04', '科技创新类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('284', 'jg00000030', '05', '学术竞赛优胜类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('285', 'jg00000030', '06', '艺术杰出类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('286', 'jg00000030', '07', '体育优胜类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('287', 'jg00000030', '08', '社会实践优秀类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('288', 'jg00000030', '09', '社会工作优秀类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('289', 'jg00000030', '10', '自立自强逆境成才类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('290', 'jg00000030', '11', '杰出志愿者类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('291', 'jg00000030', '12', '学习进步突出类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('292', 'jg00000030', '13', '少数民族优秀学生类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('293', 'jg00000030', '14', '港澳台侨优秀学生类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('294', 'jg00000030', '15', '优秀新生类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('295', 'jg00000030', '30', '来华留学生奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('296', 'jg00000030', '31', '中国政府奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('297', 'jg00000030', '32', '外国汉语教师短期研修奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('298', 'jg00000030', '33', 'HSK优胜者奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('299', 'jg00000030', '34', '中华文化研究奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('300', 'jg00000030', '35', '长城奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('301', 'jg00000030', '36', '优秀生奖学金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('302', 'jg00000030', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('303', 'jg00000031', '1', '应聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('304', 'jg00000031', '2', '挂职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('305', 'jg00000031', '3', '定向', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('306', 'jg00000031', '4', '委培', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('307', 'jg00000031', '5', '创业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('308', 'jg00000031', '6', '出国（境）工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('309', 'jg00000031', '7', '博士后', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('310', 'jg00000031', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('311', 'jg00000032', '1', '特别困难', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('312', 'jg00000032', '2', '一般困难', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('313', 'jg00000032', '21', '困难', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('314', 'jg00000032', '22', '一般困难', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('315', 'jg00000032', '3', '不困难', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('316', 'jg00000033', '1', '自然灾害', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('317', 'jg00000033', '2', '突发意外', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('318', 'jg00000033', '3', '家庭成员病残', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('319', 'jg00000034', '1', '国际组织资助', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('320', 'jg00000034', '11', '外国基金会奖学金', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('321', 'jg00000034', '2', '中国政府资助', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('322', 'jg00000034', '21', '中国政府奖学金', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('323', 'jg00000034', '22', '中国地方政府奖学金', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('324', 'jg00000034', '23', '中国政府各部门奖学金', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('325', 'jg00000034', '3', '本国政府资助', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('326', 'jg00000034', '31', '外国政府奖学金', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('327', 'jg00000034', '4', '学校间交换', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('328', 'jg00000034', '41', '校际交流', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('329', 'jg00000034', '42', '中国学校奖学金', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('330', 'jg00000034', '43', '外国学校奖学金', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('331', 'jg00000034', '5', '自费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('332', 'jg00000034', '6', '企业资助', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('333', 'jg00000034', '61', '中国企业奖学金', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('334', 'jg00000034', '62', '外国企业奖学金', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('335', 'jg00000035', '01', '专科生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('336', 'jg00000035', '02', '本科生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('337', 'jg00000035', '03', '硕士研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('338', 'jg00000035', '04', '博士研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('339', 'jg00000035', '05', '语言生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('340', 'jg00000035', '06', '普通进修生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('341', 'jg00000035', '07', '高级进修生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('342', 'jg00000035', '08', '研究学者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('343', 'jg00000035', '09', '预科生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('344', 'jg00000036', '1', '学费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('345', 'jg00000036', '2', '住宿费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('346', 'jg00000036', '4', '注册费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('347', 'jg00000036', '5', '保险费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('348', 'jg00000036', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('349', 'jg00000037', '1', '驻华使馆外交官子女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('350', 'jg00000037', '2', '外国友好人士子女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('351', 'jg00000037', '3', '重要人物子女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('352', 'jg00000037', '4', '重要人物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('353', 'jg00000037', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('354', 'jg00000038', '01', '统一招生考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('355', 'jg00000038', '02', '保送（或免试）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('356', 'jg00000038', '03', '学生干部保送', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('357', 'jg00000038', '04', '考试推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('358', 'jg00000038', '05', '国防定向培养', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('359', 'jg00000038', '06', '其他定向培养', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('360', 'jg00000038', '07', '代培', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('361', 'jg00000038', '08', '第二学位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('362', 'jg00000038', '09', '港澳', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('363', 'jg00000038', '10', '港澳代培', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('364', 'jg00000038', '11', '台湾', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('365', 'jg00000038', '12', '内地班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('366', 'jg00000038', '13', '民族班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('367', 'jg00000038', '14', '体育特招', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('368', 'jg00000038', '15', '文艺特招', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('369', 'jg00000038', '16', '预科班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('370', 'jg00000038', '17', '恢复入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('371', 'jg00000038', '18', '外校转入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('372', 'jg00000038', '19', '自主招生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('373', 'jg00000038', '20', '国内交换生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('374', 'jg00000038', '21', '境外交换生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('375', 'jg00000038', '22', '来华留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('376', 'jg00000038', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('377', 'jg00000039', '1', '博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('378', 'jg00000039', '2', '硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('379', 'jg00000039', '3', '本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('380', 'jg00000039', '4', '专科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('381', 'jg00000039', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('382', 'jg00000040', '10', '国家任务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('383', 'jg00000040', '11', '非定向', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('384', 'jg00000040', '12', '定向', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('385', 'jg00000040', '20', '非国家任务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('386', 'jg00000040', '21', '自筹经费', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('387', 'jg00000040', '22', '委托培养', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('388', 'jg00000040', '23', '联合培养', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('389', 'jg00000040', '30', '留学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('390', 'jg00000040', '31', '国际组织资助', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('391', 'jg00000040', '32', '中国政府资助', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('392', 'jg00000040', '33', '本国政府资助', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('393', 'jg00000040', '34', '学校间交换', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('394', 'jg00000040', '35', '自费留学生', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('395', 'jg00000040', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('396', 'jg00000041', '1', '辅助教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('397', 'jg00000041', '2', '辅助科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('398', 'jg00000041', '3', '辅助管理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('399', 'jg00000041', '4', '卫生清洁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('400', 'jg00000041', '5', '校园保安', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('401', 'jg00000041', '6', '校园修缮', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('402', 'jg00000041', '7', '家教', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('403', 'jg00000041', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('404', 'jg00000042', '01', '统一招生考试/普通入学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('405', 'jg00000042', '02', '保送', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('406', 'jg00000042', '03', '民族班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('407', 'jg00000042', '04', '定向培养', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('408', 'jg00000042', '05', '体育特招', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('409', 'jg00000042', '06', '文艺特招', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('410', 'jg00000042', '07', '学生干部保送', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('411', 'jg00000042', '08', '考试推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('412', 'jg00000042', '09', '外校转入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('413', 'jg00000042', '10', '恢复入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('414', 'jg00000042', '11', '预科班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('415', 'jg00000042', '12', '来华留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('416', 'jg00000042', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('417', 'jg00000043', '1', '师范类学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('418', 'jg00000043', '11', '中等师范学校师范生', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('419', 'jg00000043', '111', '中等师范学校师范生', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('420', 'jg00000043', '12', '高等师范院校师范生（本科）', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('421', 'jg00000043', '121', '高等师范院校普通师范生（本科）', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('422', 'jg00000043', '122', '高等师范院校免费师范生（本科）', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('423', 'jg00000043', '13', '教育硕士', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('424', 'jg00000043', '131', '在职普通教育硕士', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('425', 'jg00000043', '132', '全日制普通教育硕士', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('426', 'jg00000043', '133', '免费师范生攻读教育硕士', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('427', 'jg00000043', '134', '农村教育硕士（硕师计划）', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('428', 'jg00000043', '14', '教育博士', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('429', 'jg00000043', '141', '教育博士', '', '', '14', '');
INSERT INTO `uc_dict_item` VALUES ('430', 'jg00000043', '2', '非师范类学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('431', 'jg00000044', '1', '校级及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('432', 'jg00000044', '2', '院系级及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('433', 'jg00000045', '01', '科研技术开发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('434', 'jg00000045', '02', '信息技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('435', 'jg00000045', '03', '技术改造', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('436', 'jg00000045', '04', '规划设计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('437', 'jg00000045', '05', '工艺美术设计与研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('438', 'jg00000045', '06', '技术、管理及各类专业业务培训', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('439', 'jg00000045', '07', '地区或单位发展研究或规划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('440', 'jg00000045', '08', '地方党政部门的管理及研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('441', 'jg00000045', '09', '政策咨询', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('442', 'jg00000045', '10', '中外文资料翻译、文字整理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('443', 'jg00000045', '11', '接受单位大型活动的组织策划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('444', 'jg00000045', '12', '行政事务性工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('445', 'jg00000045', '13', '文艺演出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('446', 'jg00000045', '14', '随队医疗或医药咨询', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('447', 'jg00000045', '15', '中小学兼职教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('448', 'jg00000045', '21', '就业实践', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('449', 'jg00000045', '22', '短期挂职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('450', 'jg00000045', '23', '认知考察', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('451', 'jg00000045', '24', '博士后实践服务团', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('452', 'jg00000045', '25', '志愿者工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('453', 'jg00000045', '99', '其他相应工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('454', 'jg00000046', '1', '助教', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('455', 'jg00000046', '2', '助研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('456', 'jg00000046', '3', '助管', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('457', 'jg00000047', '01', '内科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('458', 'jg00000047', '02', '外科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('459', 'jg00000047', '03', '眼科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('460', 'jg00000047', '04', '耳鼻喉科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('461', 'jg00000047', '05', '口腔科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('462', 'jg00000047', '06', '妇科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('463', 'jg00000047', '07', '血压', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('464', 'jg00000047', '08', '胸透', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('465', 'jg00000047', '09', '生化检查', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('466', 'jg00000047', '10', '心电图', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('467', 'jg00000047', '11', 'B超', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('468', 'jg00000047', '12', '身高', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('469', 'jg00000047', '13', '体重', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('470', 'jg00000047', '14', '体能、形态', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('471', 'jg00000047', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('472', 'jg00000048', '01', '触犯刑法', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('473', 'jg00000048', '02', '违反治安管理条例', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('474', 'jg00000048', '03', '学业违纪（考试作弊、旷课）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('475', 'jg00000048', '04', '涂改、伪造证件或证明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('476', 'jg00000048', '05', '侵犯他人人身权利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('477', 'jg00000048', '06', '侵犯公私财物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('478', 'jg00000048', '07', '侵犯学校权益', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('479', 'jg00000048', '08', '危害公共安全', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('480', 'jg00000048', '09', '扰乱公共场所秩序', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('481', 'jg00000048', '10', '违反社团管理规定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('482', 'jg00000048', '11', '违反课外活动规定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('483', 'jg00000048', '12', '违反宿舍管理规定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('484', 'jg00000048', '13', '违反网络管理规定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('485', 'jg00000048', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('486', 'jg00000049', '01', '公派留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('487', 'jg00000049', '02', '留级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('488', 'jg00000049', '03', '降级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('489', 'jg00000049', '04', '跳级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('490', 'jg00000049', '05', '试读', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('491', 'jg00000049', '06', '延长年限', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('492', 'jg00000049', '07', '试读通过', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('493', 'jg00000049', '08', '回国复学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('494', 'jg00000049', '11', '休学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('495', 'jg00000049', '12', '复学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('496', 'jg00000049', '13', '停学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('497', 'jg00000049', '14', '保留入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('498', 'jg00000049', '15', '恢复入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('499', 'jg00000049', '16', '取消入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('500', 'jg00000049', '17', '恢复学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('501', 'jg00000049', '18', '取消学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('502', 'jg00000049', '19', '保留学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('503', 'jg00000049', '21', '转学（转出）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('504', 'jg00000049', '22', '转学（转入）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('505', 'jg00000049', '23', '转专业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('506', 'jg00000049', '24', '专升本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('507', 'jg00000049', '25', '本转专', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('508', 'jg00000049', '26', '转系', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('509', 'jg00000049', '27', '硕转博', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('510', 'jg00000049', '28', '博转硕', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('511', 'jg00000049', '29', '转班级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('512', 'jg00000049', '31', '退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('513', 'jg00000049', '42', '开除学籍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('514', 'jg00000049', '51', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('515', 'jg00000049', '61', '提前毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('516', 'jg00000049', '62', '结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('517', 'jg00000049', '63', '肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('518', 'jg00000049', '64', '国内访学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('519', 'jg00000049', '65', '国内访学后返校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('520', 'jg00000049', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('521', 'jg00000050', '1', '成绩优秀', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('522', 'jg00000050', '10', '疾病', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('523', 'jg00000050', '11', '精神疾病', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('524', 'jg00000050', '12', '传染疾病', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('525', 'jg00000050', '19', '其他疾病', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('526', 'jg00000050', '21', '自动退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('527', 'jg00000050', '22', '成绩太差', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('528', 'jg00000050', '23', '触犯刑法', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('529', 'jg00000050', '24', '休学创业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('530', 'jg00000050', '25', '停学实践（求职）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('531', 'jg00000050', '26', '家长病重', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('532', 'jg00000050', '27', '贫困', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('533', 'jg00000050', '28', '非留学出国（境）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('534', 'jg00000050', '29', '自费出国退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('535', 'jg00000050', '30', '自费留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('536', 'jg00000050', '31', '休学期满未办理复学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('537', 'jg00000050', '32', '恶意欠缴学费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('538', 'jg00000050', '33', '超过最长学习期限未完成学业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('539', 'jg00000050', '34', '应征入伍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('540', 'jg00000050', '39', '其他原因退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('541', 'jg00000050', '4/5/6', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('542', 'jg00000050', '40', '事故灾难致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('543', 'jg00000050', '41', '溺水死亡', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('544', 'jg00000050', '42', '交通事故致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('545', 'jg00000050', '43', '拥挤踩踏致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('546', 'jg00000050', '44', '房屋倒塌致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('547', 'jg00000050', '45', '坠楼坠崖致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('548', 'jg00000050', '46', '中毒致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('549', 'jg00000050', '47', '爆炸致死', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('550', 'jg00000050', '50', '社会安全事件致死', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('551', 'jg00000050', '51', '打架斗殴致死', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('552', 'jg00000050', '52', '校园伤害致死', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('553', 'jg00000050', '53', '刑事案件致死', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('554', 'jg00000050', '54', '火灾致死', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('555', 'jg00000050', '60', '自然灾害致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('556', 'jg00000050', '61', '山体滑坡致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('557', 'jg00000050', '62', '泥石流致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('558', 'jg00000050', '63', '洪水致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('559', 'jg00000050', '64', '地震致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('560', 'jg00000050', '65', '暴雨致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('561', 'jg00000050', '66', '冰雹致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('562', 'jg00000050', '67', '雪灾致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('563', 'jg00000050', '68', '龙卷风致死', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('564', 'jg00000050', '70', '因病死亡', '', '', '7', '');
INSERT INTO `uc_dict_item` VALUES ('565', 'jg00000050', '71', '传染病致死', '', '', '7', '');
INSERT INTO `uc_dict_item` VALUES ('566', 'jg00000050', '72', '猝死', '', '', '7', '');
INSERT INTO `uc_dict_item` VALUES ('567', 'jg00000050', '79', '其他疾病致死', '', '', '7', '');
INSERT INTO `uc_dict_item` VALUES ('568', 'jg00000050', '81', '自杀死亡', '', '', '8', '');
INSERT INTO `uc_dict_item` VALUES ('569', 'jg00000050', '89', '其他原因死亡', '', '', '8', '');
INSERT INTO `uc_dict_item` VALUES ('570', 'jg00000050', '99', '其他', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('571', 'jg00000051', '11', '招生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('572', 'jg00000051', '12', '复学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('573', 'jg00000051', '13', '转入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('574', 'jg00000051', '21', '毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('575', 'jg00000051', '22', '结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('576', 'jg00000051', '23', '休学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('577', 'jg00000051', '24', '退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('578', 'jg00000051', '25', '开除', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('579', 'jg00000051', '26', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('580', 'jg00000051', '27', '转出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('581', 'jg00000051', '28', '辍学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('582', 'jg00000052', '01', '在读', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('583', 'jg00000052', '02', '休学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('584', 'jg00000052', '03', '退学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('585', 'jg00000052', '04', '停学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('586', 'jg00000052', '05', '复学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('587', 'jg00000052', '06', '流失', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('588', 'jg00000052', '07', '毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('589', 'jg00000052', '08', '结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('590', 'jg00000052', '09', '肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('591', 'jg00000052', '10', '转学（转出）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('592', 'jg00000052', '11', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('593', 'jg00000052', '12', '保留入学资格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('594', 'jg00000052', '13', '公派出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('595', 'jg00000052', '14', '开除', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('596', 'jg00000052', '15', '下落不明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('597', 'jg00000052', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('598', 'jg00000053', '1', '学科获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('599', 'jg00000053', '2', '科技获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('600', 'jg00000053', '3', '文艺获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('601', 'jg00000053', '4', '体育获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('602', 'jg00000053', '5', '综合获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('603', 'jg00000053', '6', '社会工作获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('604', 'jg00000053', '7', '公益事业获奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('605', 'jg00000053', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('606', 'jg00000054', '1', '学前教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('607', 'jg00000054', '11', '幼儿', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('608', 'jg00000054', '11100', '幼儿', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('609', 'jg00000054', '2', '初等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('610', 'jg00000054', '21', '小学生', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('611', 'jg00000054', '21100', '普通小学生', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('612', 'jg00000054', '21200', '成人小学生', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('613', 'jg00000054', '3', '中等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('614', 'jg00000054', '31', '初中生', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('615', 'jg00000054', '31100', '普通初中学生', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('616', 'jg00000054', '31200', '职业初中学生', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('617', 'jg00000054', '31300', '成人初中学生', '', '', '31', '');
INSERT INTO `uc_dict_item` VALUES ('618', 'jg00000054', '32', '高中生', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('619', 'jg00000054', '32100', '普通高中学生', '', '', '32', '');
INSERT INTO `uc_dict_item` VALUES ('620', 'jg00000054', '32200', '成人高中学生', '', '', '32', '');
INSERT INTO `uc_dict_item` VALUES ('621', 'jg00000054', '33', '中职学生', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('622', 'jg00000054', '331', '调整后中职学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('623', 'jg00000054', '33110', '调整后中职全日制学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('624', 'jg00000054', '33120', '调整后中职非全日制学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('625', 'jg00000054', '33200', '普通中专学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('626', 'jg00000054', '333', '成人中专学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('627', 'jg00000054', '33310', '成人中专全日制学生', '', '', '333', '');
INSERT INTO `uc_dict_item` VALUES ('628', 'jg00000054', '33320', '成人中专非全日制学生', '', '', '333', '');
INSERT INTO `uc_dict_item` VALUES ('629', 'jg00000054', '33400', '职业高中学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('630', 'jg00000054', '33500', '技工学校学生', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('631', 'jg00000054', '34', '工读学生', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('632', 'jg00000054', '4', '高等教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('633', 'jg00000054', '41', '专科生', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('634', 'jg00000054', '411', '普通专科生', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('635', 'jg00000054', '41101', '高中起点专科生', '', '', '411', '');
INSERT INTO `uc_dict_item` VALUES ('636', 'jg00000054', '41102', '对口招收中职生', '', '', '411', '');
INSERT INTO `uc_dict_item` VALUES ('637', 'jg00000054', '41103', '五年制高职转入生', '', '', '411', '');
INSERT INTO `uc_dict_item` VALUES ('638', 'jg00000054', '412', '成人专科生', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('639', 'jg00000054', '4121', '函授专科生', '', '', '412', '');
INSERT INTO `uc_dict_item` VALUES ('640', 'jg00000054', '41211', '函授高中起点专科生', '', '', '4121', '');
INSERT INTO `uc_dict_item` VALUES ('641', 'jg00000054', '41212', '函授专科第二学历', '', '', '4121', '');
INSERT INTO `uc_dict_item` VALUES ('642', 'jg00000054', '4122', '业余专科生', '', '', '412', '');
INSERT INTO `uc_dict_item` VALUES ('643', 'jg00000054', '41221', '业余高中起点专科生', '', '', '4122', '');
INSERT INTO `uc_dict_item` VALUES ('644', 'jg00000054', '41222', '业余专科第二学历', '', '', '4122', '');
INSERT INTO `uc_dict_item` VALUES ('645', 'jg00000054', '4123', '脱产专科', '', '', '412', '');
INSERT INTO `uc_dict_item` VALUES ('646', 'jg00000054', '41231', '脱产高中起点专科生', '', '', '4123', '');
INSERT INTO `uc_dict_item` VALUES ('647', 'jg00000054', '41232', '脱产专科第二学历', '', '', '4123', '');
INSERT INTO `uc_dict_item` VALUES ('648', 'jg00000054', '413', '网络专科生', '', '', '41', '');
INSERT INTO `uc_dict_item` VALUES ('649', 'jg00000054', '41301', '网络高中起点专科生', '', '', '413', '');
INSERT INTO `uc_dict_item` VALUES ('650', 'jg00000054', '42', '本科生', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('651', 'jg00000054', '421', '普通本科生', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('652', 'jg00000054', '42101', '高中起点本科生', '', '', '421', '');
INSERT INTO `uc_dict_item` VALUES ('653', 'jg00000054', '42102', '专科起点本科生', '', '', '421', '');
INSERT INTO `uc_dict_item` VALUES ('654', 'jg00000054', '42103', '第二学士学位', '', '', '421', '');
INSERT INTO `uc_dict_item` VALUES ('655', 'jg00000054', '422', '成人本科生', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('656', 'jg00000054', '4221', '函授本科', '', '', '422', '');
INSERT INTO `uc_dict_item` VALUES ('657', 'jg00000054', '42211', '函授高中起点本科生', '', '', '4221', '');
INSERT INTO `uc_dict_item` VALUES ('658', 'jg00000054', '42212', '函授专科起点本科生', '', '', '4221', '');
INSERT INTO `uc_dict_item` VALUES ('659', 'jg00000054', '4222', '业余本科', '', '', '422', '');
INSERT INTO `uc_dict_item` VALUES ('660', 'jg00000054', '42221', '业余高中起点本科生', '', '', '4222', '');
INSERT INTO `uc_dict_item` VALUES ('661', 'jg00000054', '42222', '业余专科起点本科生', '', '', '4222', '');
INSERT INTO `uc_dict_item` VALUES ('662', 'jg00000054', '4223', '脱产本科', '', '', '422', '');
INSERT INTO `uc_dict_item` VALUES ('663', 'jg00000054', '42231', '脱产高中起点本科生', '', '', '4223', '');
INSERT INTO `uc_dict_item` VALUES ('664', 'jg00000054', '42232', '脱产专科起点本科生', '', '', '4223', '');
INSERT INTO `uc_dict_item` VALUES ('665', 'jg00000054', '423', '网络本科生', '', '', '42', '');
INSERT INTO `uc_dict_item` VALUES ('666', 'jg00000054', '42301', '网络高中起点本科生', '', '', '423', '');
INSERT INTO `uc_dict_item` VALUES ('667', 'jg00000054', '42302', '网络专科起点本科生', '', '', '423', '');
INSERT INTO `uc_dict_item` VALUES ('668', 'jg00000054', '43', '研究生', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('669', 'jg00000054', '431', '硕士研究生', '', '', '43', '');
INSERT INTO `uc_dict_item` VALUES ('670', 'jg00000054', '4311', '学术型硕士研究生', '', '', '431', '');
INSERT INTO `uc_dict_item` VALUES ('671', 'jg00000054', '43111', '国家任务学术型学位硕士研究生', '', '', '4311', '');
INSERT INTO `uc_dict_item` VALUES ('672', 'jg00000054', '43112', '委托培养学术型学位硕士研究生', '', '', '4311', '');
INSERT INTO `uc_dict_item` VALUES ('673', 'jg00000054', '43113', '自筹经费学术型学位硕士研究生', '', '', '4311', '');
INSERT INTO `uc_dict_item` VALUES ('674', 'jg00000054', '4312', '专业学位硕士研究生', '', '', '431', '');
INSERT INTO `uc_dict_item` VALUES ('675', 'jg00000054', '43121', '国家任务专业学位硕士研究生', '', '', '4312', '');
INSERT INTO `uc_dict_item` VALUES ('676', 'jg00000054', '43122', '委托培养专业学位硕士研究生', '', '', '4312', '');
INSERT INTO `uc_dict_item` VALUES ('677', 'jg00000054', '43123', '自筹经费专业学位硕士研究生', '', '', '4312', '');
INSERT INTO `uc_dict_item` VALUES ('678', 'jg00000054', '432', '博士研究生', '', '', '43', '');
INSERT INTO `uc_dict_item` VALUES ('679', 'jg00000054', '4321', '学术型博士研究生', '', '', '432', '');
INSERT INTO `uc_dict_item` VALUES ('680', 'jg00000054', '43211', '国家任务学术型学位博士研究生', '', '', '4321', '');
INSERT INTO `uc_dict_item` VALUES ('681', 'jg00000054', '43212', '委托培养学术型学位博士研究生', '', '', '4321', '');
INSERT INTO `uc_dict_item` VALUES ('682', 'jg00000054', '43213', '自筹经费学术型学位博士研究生', '', '', '4321', '');
INSERT INTO `uc_dict_item` VALUES ('683', 'jg00000054', '4322', '专业学位博士研究生', '', '', '432', '');
INSERT INTO `uc_dict_item` VALUES ('684', 'jg00000054', '43221', '国家任务专业学位博士研究生', '', '', '4322', '');
INSERT INTO `uc_dict_item` VALUES ('685', 'jg00000054', '43222', '委托培养专业学位博士研究生', '', '', '4322', '');
INSERT INTO `uc_dict_item` VALUES ('686', 'jg00000054', '43223', '自筹经费专业学位博士研究生', '', '', '4322', '');
INSERT INTO `uc_dict_item` VALUES ('687', 'jg00000054', '44', '学位学生', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('688', 'jg00000054', '441', '学士学位学生', '', '', '44', '');
INSERT INTO `uc_dict_item` VALUES ('689', 'jg00000054', '44110', '学术型学位学士学位学生', '', '', '441', '');
INSERT INTO `uc_dict_item` VALUES ('690', 'jg00000054', '44120', '专业学位学士学位学生', '', '', '441', '');
INSERT INTO `uc_dict_item` VALUES ('691', 'jg00000054', '442', '硕士学位学生', '', '', '44', '');
INSERT INTO `uc_dict_item` VALUES ('692', 'jg00000054', '44210', '学术型学位硕士学位学生', '', '', '442', '');
INSERT INTO `uc_dict_item` VALUES ('693', 'jg00000054', '44220', '专业学位硕士学位学生', '', '', '442', '');
INSERT INTO `uc_dict_item` VALUES ('694', 'jg00000054', '443', '博士学位学生', '', '', '44', '');
INSERT INTO `uc_dict_item` VALUES ('695', 'jg00000054', '44310', '学术型学位博士学位学生', '', '', '443', '');
INSERT INTO `uc_dict_item` VALUES ('696', 'jg00000054', '44320', '专业学位博士学位学生', '', '', '443', '');
INSERT INTO `uc_dict_item` VALUES ('697', 'jg00000054', '5', '特殊教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('698', 'jg00000054', '51', '特殊教育学生', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('699', 'jg00000054', '51100', '视力残疾学生', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('700', 'jg00000054', '51200', '听力残疾学生', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('701', 'jg00000054', '51300', '智力残疾学生', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('702', 'jg00000054', '51400', '其他残疾学生', '', '', '51', '');
INSERT INTO `uc_dict_item` VALUES ('703', 'jg00000054', '9', '其他教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('704', 'jg00000054', '91', '自考/预科/研究生课程类', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('705', 'jg00000054', '91100', '自考助学班学生', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('706', 'jg00000054', '91200', '电大注册视听生', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('707', 'jg00000054', '91300', '学历文凭考试学生', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('708', 'jg00000054', '91400', '普通预科生', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('709', 'jg00000054', '91500', '研究生课程进修班学生', '', '', '91', '');
INSERT INTO `uc_dict_item` VALUES ('710', 'jg00000054', '92', '进修及培训类', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('711', 'jg00000054', '92100', '进修学生', '', '', '92', '');
INSERT INTO `uc_dict_item` VALUES ('712', 'jg00000054', '92200', '培训学生', '', '', '92', '');
INSERT INTO `uc_dict_item` VALUES ('713', 'jg00000054', '92300', '岗位证书培训学生', '', '', '92', '');
INSERT INTO `uc_dict_item` VALUES ('714', 'jg00000054', '92400', '资格证书培训学生', '', '', '92', '');
INSERT INTO `uc_dict_item` VALUES ('715', 'jg00000054', '93', '职业技术培训类', '', '', '9', '');
INSERT INTO `uc_dict_item` VALUES ('716', 'jg00000054', '93100', '职工技术培训学生', '', '', '93', '');
INSERT INTO `uc_dict_item` VALUES ('717', 'jg00000054', '93200', '农村成人文化技术培训学生', '', '', '93', '');
INSERT INTO `uc_dict_item` VALUES ('718', 'jg00000054', '93300', '其他培训学生学生', '', '', '93', '');
INSERT INTO `uc_dict_item` VALUES ('719', 'jg00000055', '000', '研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('720', 'jg00000055', '001', '科技人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('721', 'jg00000055', '002', '高校教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('722', 'jg00000055', '003', '中学教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('723', 'jg00000055', '004', '其他在职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('724', 'jg00000055', '005', '应届本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('725', 'jg00000055', '006', '成人应届本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('726', 'jg00000055', '007', '网络教育应届本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('727', 'jg00000055', '008', '其他人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('728', 'jg00000055', '100', '普通高校本专科/中等职业学校', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('729', 'jg00000055', '101', '城镇应届', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('730', 'jg00000055', '102', '农村应届', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('731', 'jg00000055', '103', '城镇往届', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('732', 'jg00000055', '104', '农村往届', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('733', 'jg00000055', '105', '工人', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('734', 'jg00000055', '106', '干部', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('735', 'jg00000055', '107', '复退军人', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('736', 'jg00000055', '108', '现役军人', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('737', 'jg00000055', '109', '香港生', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('738', 'jg00000055', '110', '澳门生', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('739', 'jg00000055', '111', '台湾生', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('740', 'jg00000055', '112', '归国华侨/港澳台侨', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('741', 'jg00000055', '113', '留学生/外籍', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('742', 'jg00000055', '199', '其他', '', '', '100', '');
INSERT INTO `uc_dict_item` VALUES ('743', 'jg00000055', '200', '成人高校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('744', 'jg00000055', '201', '国家机关、党群组织、企业、事业单位负责人', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('745', 'jg00000055', '209', '军人', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('746', 'jg00000055', '211', '专业技术人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('747', 'jg00000055', '231', '办事人员和有关人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('748', 'jg00000055', '241', '商业、服务业人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('749', 'jg00000055', '251', '农、林、牧、渔、水利业生产人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('750', 'jg00000055', '261', '生产、运输设备操作人员及有关人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('751', 'jg00000055', '299', '不便分类的其他从业人员', '', '', '200', '');
INSERT INTO `uc_dict_item` VALUES ('752', 'jg00000056', '1', '3 岁以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('753', 'jg00000056', '2', '3岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('754', 'jg00000056', '3', '4岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('755', 'jg00000056', '4', '5 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('756', 'jg00000056', '5', '5岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('757', 'jg00000056', '6', '5 岁以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('758', 'jg00000056', '7', '6岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('759', 'jg00000056', '8', '7岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('760', 'jg00000056', '9', '8岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('761', 'jg00000056', '10', '9岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('762', 'jg00000056', '11', '10 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('763', 'jg00000056', '12', '10 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('764', 'jg00000056', '13', '11 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('765', 'jg00000056', '14', '12 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('766', 'jg00000056', '15', '13 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('767', 'jg00000056', '16', '14 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('768', 'jg00000056', '17', '14 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('769', 'jg00000056', '18', '15 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('770', 'jg00000056', '19', '15 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('771', 'jg00000056', '20', '16 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('772', 'jg00000056', '21', '17 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('773', 'jg00000056', '22', '17 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('774', 'jg00000056', '23', '18 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('775', 'jg00000056', '24', '18 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('776', 'jg00000056', '25', '19 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('777', 'jg00000056', '26', '20 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('778', 'jg00000056', '27', '21 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('779', 'jg00000056', '28', '22 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('780', 'jg00000056', '29', '22 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('781', 'jg00000056', '30', '23 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('782', 'jg00000056', '31', '24 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('783', 'jg00000056', '32', '25 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('784', 'jg00000056', '33', '26 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('785', 'jg00000056', '34', '27 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('786', 'jg00000056', '35', '28 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('787', 'jg00000056', '36', '29 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('788', 'jg00000056', '37', '30 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('789', 'jg00000056', '38', '31 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('790', 'jg00000057', '1', '减', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('791', 'jg00000057', '2', '免', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('792', 'jg00000057', '3', '缓', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('793', 'jg00000057', '4', '贷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('794', 'jg00000057', '5', '缓+减', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('795', 'jg00000057', '6', '缓+免', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('796', 'jg00000057', '7', '缓+贷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('797', 'jg00000058', '1', '优秀', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('798', 'jg00000058', '2', '良好', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('799', 'jg00000058', '3', '及格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('800', 'jg00000058', '4', '不及格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('801', 'jg00000059', '1', '病患', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('802', 'jg00000059', '2', '停学实践（求职）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('803', 'jg00000059', '3', '贫困', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('804', 'jg00000059', '4', '学习成绩不好', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('805', 'jg00000059', '5', '出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('806', 'jg00000059', '6', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('807', 'jg00000060', '1', '调剂入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('808', 'jg00000060', '2', '调剂出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('809', 'jg00000061', '1', '强军计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('810', 'jg00000061', '2', '援藏计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('811', 'jg00000061', '3', '农村师资计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('812', 'jg00000061', '4', '少数民族骨干计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('813', 'jg00000061', '5', '高校辅导员专项', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('814', 'jg00000061', '6', '两课教师专项', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('815', 'jg00000061', '0', '无专项计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('816', 'jg00000062', '1', '纠集他人结伙滋事，扰乱治安', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('817', 'jg00000062', '2', '携带管制刀具，屡教不改', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('818', 'jg00000062', '3', '多次拦截殴打他人或者强行索要他人财物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('819', 'jg00000062', '4', '传播淫秽的读物或者音像制品等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('820', 'jg00000062', '5', '进行淫乱或者色情、卖淫活动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('821', 'jg00000062', '6', '多次偷窃', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('822', 'jg00000062', '7', '参与赌博，屡教不改', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('823', 'jg00000062', '8', '吸食、注射毒品', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('824', 'jg00000062', '9', '其他严重危害社会的行为', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('825', 'jg00000063', '10', '博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('826', 'jg00000063', '11', '博士生公开招考', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('827', 'jg00000063', '12', '博士生推荐免试', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('828', 'jg00000063', '13', '硕士博士连读', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('829', 'jg00000063', '14', '本科硕士博士生连读', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('830', 'jg00000063', '15', '博士生港澳台考试', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('831', 'jg00000063', '16', '来华留学博士生考试', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('832', 'jg00000063', '17', '本科毕业生直接攻博', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('833', 'jg00000063', '18', '硕士生提前攻博', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('834', 'jg00000063', '19', '博士生保留资格返校', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('835', 'jg00000063', '20', '硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('836', 'jg00000063', '21', '硕士生全国统考', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('837', 'jg00000063', '22', '硕士生推荐免试', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('838', 'jg00000063', '23', '硕士生单独考试', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('839', 'jg00000063', '24', '硕士生保留资格返校', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('840', 'jg00000063', '25', '硕士生港澳台考试', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('841', 'jg00000063', '26', '来华留学硕士生考试', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('842', 'jg00000063', '30', '硕士专业学位联考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('843', 'jg00000063', '31', '经济类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('844', 'jg00000063', '32', '法律类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('845', 'jg00000063', '33', '教育类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('846', 'jg00000063', '34', '文学类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('847', 'jg00000063', '35', '文博类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('848', 'jg00000063', '36', '工程类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('849', 'jg00000063', '37', '农学类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('850', 'jg00000063', '38', '医学类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('851', 'jg00000063', '39', '军事类联考', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('852', 'jg00000063', '40', '管理类联考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('853', 'jg00000063', '41', '艺术类联考', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('854', 'jg00000063', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('855', 'jg00000064', '1', '校园专场招聘会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('856', 'jg00000064', '2', '校园集中洽谈会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('857', 'jg00000064', '3', '社会招聘会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('858', 'jg00000064', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('859', 'jg00000065', '1', '正常入学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('860', 'jg00000065', '2', '借读', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('861', 'jg00000065', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('862', 'jg00000066', '1', '25 人及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('863', 'jg00000066', '2', '26 至 35 人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('864', 'jg00000066', '3', '36 至 45 人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('865', 'jg00000066', '4', '46 至 55 人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('866', 'jg00000066', '5', '56 至 65 人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('867', 'jg00000066', '6', '66 人及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('868', 'jg00000067', '1', '主考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('869', 'jg00000067', '2', '副主考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('870', 'jg00000067', '3', '一般监考人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('871', 'jg00000067', '4', '巡考人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('872', 'jg00000067', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('873', 'jg00000068', '1', '多媒体教室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('874', 'jg00000068', '2', '语音室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('875', 'jg00000068', '3', '实验室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('876', 'jg00000068', '4', '计算机房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('877', 'jg00000068', '5', '普通教室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('878', 'jg00000068', '6', '专用教室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('879', 'jg00000068', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('880', 'jg00000069', '1', '讲课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('881', 'jg00000069', '2', '讲座', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('882', 'jg00000069', '3', '考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('883', 'jg00000069', '4', '实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('884', 'jg00000069', '5', '社团活动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('885', 'jg00000069', '6', '空闲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('886', 'jg00000069', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('887', 'jg00000070', '11', '讲课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('888', 'jg00000070', '12', '编写教材', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('889', 'jg00000070', '13', '辅导', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('890', 'jg00000070', '21', '带实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('891', 'jg00000070', '22', '带实习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('892', 'jg00000070', '23', '带设计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('893', 'jg00000070', '24', '带社会实践', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('894', 'jg00000070', '25', '指导论文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('895', 'jg00000070', '31', '带军训', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('896', 'jg00000070', '32', '带学工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('897', 'jg00000070', '33', '带学农', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('898', 'jg00000070', '41', '班主任', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('899', 'jg00000070', '42', '指导员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('900', 'jg00000070', '43', '政治辅导员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('901', 'jg00000070', '51', '指导研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('902', 'jg00000070', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('903', 'jg00000071', '1', '教室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('904', 'jg00000071', '10', '实验室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('905', 'jg00000071', '11', '物理实验室', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('906', 'jg00000071', '12', '化学实验室', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('907', 'jg00000071', '13', '生物实验室', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('908', 'jg00000071', '14', '其他实验室', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('909', 'jg00000071', '20', '音乐室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('910', 'jg00000071', '30', '语音室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('911', 'jg00000071', '31', '英语', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('912', 'jg00000071', '39', '其他语言', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('913', 'jg00000071', '40', '计算机房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('914', 'jg00000071', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('915', 'jg00000072', '1', '国家级精品课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('916', 'jg00000072', '2', '省级精品课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('917', 'jg00000072', '3', '校级精品课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('918', 'jg00000072', '4', '校级重点课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('919', 'jg00000072', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('920', 'jg00000073', '1', '理论类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('921', 'jg00000073', '2', '语言类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('922', 'jg00000073', '3', '实验（实训）类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('923', 'jg00000073', '4', '体育类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('924', 'jg00000073', '5', '实践类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('925', 'jg00000073', '6', '艺术类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('926', 'jg00000073', '9', '其他类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('927', 'jg00000074', '11', '本校博士生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('928', 'jg00000074', '12', '本校硕士生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('929', 'jg00000074', '13', '本校本科生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('930', 'jg00000074', '14', '本校专科生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('931', 'jg00000074', '21', '外校博士生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('932', 'jg00000074', '22', '外校硕士生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('933', 'jg00000074', '23', '外校本科生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('934', 'jg00000074', '24', '外校专科生课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('935', 'jg00000074', '50', '国外学校课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('936', 'jg00000074', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('937', 'jg00000075', '1', '必修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('938', 'jg00000075', '2', '限选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('939', 'jg00000075', '3', '任选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('940', 'jg00000075', '4', '辅修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('941', 'jg00000075', '5', '实践', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('942', 'jg00000075', '6', '双必', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('943', 'jg00000075', '7', '双选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('944', 'jg00000075', '8', '通选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('945', 'jg00000075', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('946', 'jg00000076', '1', '公共基础课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('947', 'jg00000076', '2', '学科基础课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('948', 'jg00000076', '3', '专业课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('949', 'jg00000077', '1', '笔试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('950', 'jg00000077', '2', '口试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('951', 'jg00000077', '3', '面试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('952', 'jg00000077', '4', '操作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('953', 'jg00000077', '5', '机考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('954', 'jg00000077', '6', '大作业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('955', 'jg00000077', '7', '实验报告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('956', 'jg00000077', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('957', 'jg00000078', '1', '考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('958', 'jg00000078', '2', '考查', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('959', 'jg00000079', '01', '正常考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('960', 'jg00000079', '02', '缓考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('961', 'jg00000079', '03', '旷考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('962', 'jg00000079', '11', '补考一', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('963', 'jg00000079', '12', '补考二', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('964', 'jg00000079', '13', '重修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('965', 'jg00000079', '14', '免修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('966', 'jg00000079', '21', '结业后回校补考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('967', 'jg00000079', '41', '国家级考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('968', 'jg00000079', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('969', 'jg00000080', '1', '一年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('970', 'jg00000080', '2', '二年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('971', 'jg00000080', '3', '三年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('972', 'jg00000080', '4', '三年级及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('973', 'jg00000080', '5', '四年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('974', 'jg00000080', '6', '四年级及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('975', 'jg00000080', '7', '五年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('976', 'jg00000080', '8', '五年级及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('977', 'jg00000080', '9', '六年级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('978', 'jg00000080', '10', '六年级及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('979', 'jg00000081', '1', '一个月以内', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('980', 'jg00000081', '2', '一个月至三个月内', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('981', 'jg00000081', '3', '三个月至半年内', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('982', 'jg00000081', '4', '半年至一年以内', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('983', 'jg00000081', '5', '一年及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('984', 'jg00000082', '1', '资格证书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('985', 'jg00000082', '2', '岗位证书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('986', 'jg00000083', '1', '第一产业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('987', 'jg00000083', '2', '第二产业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('988', 'jg00000083', '3', '第三产业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('989', 'jg00000084', '1', '6岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('990', 'jg00000084', '2', '7岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('991', 'jg00000084', '3', '11 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('992', 'jg00000084', '4', '12 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('993', 'jg00000084', '5', '13 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('994', 'jg00000085', '1', '缺考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('995', 'jg00000085', '2', '舞弊', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('996', 'jg00000086', '1', '面授讲课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('997', 'jg00000086', '2', '辅导', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('998', 'jg00000086', '3', '录像讲课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('999', 'jg00000086', '4', '网络教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1000', 'jg00000086', '5', '实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1001', 'jg00000086', '6', '实习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1002', 'jg00000086', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1003', 'jg00000087', '1', '一类模式', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1004', 'jg00000087', '2', '二类模式', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1005', 'jg00000087', '3', '三类模式', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1006', 'jg00000088', '1', '基础、专业基础或技术基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1007', 'jg00000088', '2', '专业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1008', 'jg00000088', '3', '科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1009', 'jg00000088', '4', '生产或技术开发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1010', 'jg00000088', '9', '其他（含毕业论文和毕业设计实验）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1011', 'jg00000089', '0', '无学位授予权', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1012', 'jg00000089', '1', '博士学位授予权', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1013', 'jg00000089', '2', '硕士学位授予权', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1014', 'jg00000089', '3', '学士学位授予权', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1015', 'jg00000090', '1', '必修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1016', 'jg00000090', '2', '选修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1017', 'jg00000090', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1018', 'jg00000091', '0', '中小学生/中职学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1019', 'jg00000091', '1', '博士生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1020', 'jg00000091', '2', '硕士生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1021', 'jg00000091', '3', '本科生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1022', 'jg00000091', '5', '教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1023', 'jg00000091', '6', '工程与实验技术人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1024', 'jg00000091', '7', '研究人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1025', 'jg00000091', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1026', 'jg00000092', '1', '小学阶段', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1027', 'jg00000092', '2', '初中阶段', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1028', 'jg00000092', '3', '高中阶段', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1029', 'jg00000092', '4', '大学阶段', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1030', 'jg00000093', '1', '秋季学期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1031', 'jg00000093', '2', '春季学期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1032', 'jg00000093', '3', '夏季学期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1033', 'jg00000093', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1034', 'jg00000094', '11', '学术型学士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1035', 'jg00000094', '12', '专业学位学士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1036', 'jg00000094', '21', '学术型硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1037', 'jg00000094', '22', '专业学位硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1038', 'jg00000094', '31', '学术型博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1039', 'jg00000094', '32', '专业学位博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1040', 'jg00000095', '1', '同意授予学位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1041', 'jg00000095', '2', '同意毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1042', 'jg00000095', '3', '缓授学位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1043', 'jg00000095', '4', '不通过', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1044', 'jg00000095', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1045', 'jg00000096', '1', '五年制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1046', 'jg00000096', '2', '六年制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1047', 'jg00000096', '3', '三年制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1048', 'jg00000096', '4', '四年制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1049', 'jg00000097', '1', '托班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1050', 'jg00000097', '2', '小班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1051', 'jg00000097', '3', '中班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1052', 'jg00000097', '4', '大班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1053', 'jg00000098', '1', '注册', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1054', 'jg00000098', '2', '报到', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1055', 'jg00000098', '3', '未报到', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1056', 'jg00000099', '111', '应届初中毕业生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1057', 'jg00000099', '211', '应届高中毕业生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1058', 'jg00000099', '311', '城镇下岗职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1059', 'jg00000099', '411', '进城务工人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1060', 'jg00000099', '420', '进城务工人员随迁子女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1061', 'jg00000099', '421', '外省迁入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1062', 'jg00000099', '422', '本省外县迁入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1063', 'jg00000099', '511', '农村留守儿童', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1064', 'jg00000099', '611', '退役士兵', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1065', 'jg00000099', '711', '农民', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1066', 'jg00000099', '811', '五年制高职中职段学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1067', 'jg00000100', '10', '普通小学班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1068', 'jg00000100', '11', '少数民族小学班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1069', 'jg00000100', '12', '小学复式班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1070', 'jg00000100', '13', '小学教学点班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1071', 'jg00000100', '14', '小学特长班（文体艺智等班）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1072', 'jg00000100', '15', '小学视力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1073', 'jg00000100', '16', '小学听力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1074', 'jg00000100', '17', '小学智力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1075', 'jg00000100', '20', '普通初中班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1076', 'jg00000100', '21', '少数民族初中班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1077', 'jg00000100', '22', '初中复式班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1078', 'jg00000100', '23', '初中教学点班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1079', 'jg00000100', '24', '初中特长班（文体艺智等班）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1080', 'jg00000100', '25', '初中视力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1081', 'jg00000100', '26', '初中听力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1082', 'jg00000100', '27', '初中智力残疾班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1083', 'jg00000100', '40', '普通高中班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1084', 'jg00000100', '41', '少数民族高中班', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1085', 'jg00000100', '42', '高中特长班（文体艺智等班）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1086', 'jg00000101', '1', '专任教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1087', 'jg00000101', '2', '行政人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1088', 'jg00000101', '3', '教辅人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1089', 'jg00000101', '4', '工勤人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1090', 'jg00000101', '5', '校办企业职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1091', 'jg00000101', '6', '代课教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1092', 'jg00000101', '7', '兼任（职）教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1093', 'jg00000101', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1094', 'jg00000102', '11', '品德与生活（社会）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1095', 'jg00000102', '12', '思想品德（政治）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1096', 'jg00000102', '13', '语文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1097', 'jg00000102', '14', '数学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1098', 'jg00000102', '15', '科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1099', 'jg00000102', '16', '物理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1100', 'jg00000102', '17', '化学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1101', 'jg00000102', '18', '生物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1102', 'jg00000102', '19', '历史与社会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1103', 'jg00000102', '20', '地理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1104', 'jg00000102', '21', '历史', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1105', 'jg00000102', '22', '体育与健康', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1106', 'jg00000102', '23', '艺术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1107', 'jg00000102', '24', '音乐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1108', 'jg00000102', '25', '美术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1109', 'jg00000102', '26', '信息技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1110', 'jg00000102', '27', '通用技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1111', 'jg00000102', '40', '外语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1112', 'jg00000102', '41', '英语', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1113', 'jg00000102', '42', '日语', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1114', 'jg00000102', '43', '俄语', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1115', 'jg00000102', '49', '其他外国语', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1116', 'jg00000102', '60', '综合实践活动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1117', 'jg00000102', '61', '信息技术', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1118', 'jg00000102', '62', '劳动与技术', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1119', 'jg00000103', '1', '国家课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1120', 'jg00000103', '2', '地方课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1121', 'jg00000103', '3', '校本课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1122', 'jg00000104', '1', '物理实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1123', 'jg00000104', '2', '化学实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1124', 'jg00000104', '3', '生物实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1125', 'jg00000104', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1126', 'jg00000105', '1', '物理实验室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1127', 'jg00000105', '2', '化学实验室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1128', 'jg00000105', '3', '生物实验室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1129', 'jg00000105', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1130', 'jg00000106', '10', '教学类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1131', 'jg00000106', '20', '行政类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1132', 'jg00000106', '30', '教辅类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1133', 'jg00000106', '40', '工勤类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1134', 'jg00000106', '50', '科研类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1135', 'jg00000106', '60', '校办企业类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1136', 'jg00000106', '70', '附设机构类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1137', 'jg00000106', '99', '其他类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1138', 'jg00000107', '1', '调至非教学岗位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1139', 'jg00000107', '2', '调至教学岗位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1140', 'jg00000107', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1141', 'jg00000108', '1', '任职期满', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1142', 'jg00000108', '2', '工作调动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1143', 'jg00000108', '3', '换届改选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1144', 'jg00000108', '4', '离退休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1145', 'jg00000108', '5', '健康原因', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1146', 'jg00000108', '6', '辞职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1147', 'jg00000108', '7', '免职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1148', 'jg00000108', '8', '处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1149', 'jg00000108', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1150', 'jg00000109', '1', '博士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1151', 'jg00000109', '2', '硕士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1152', 'jg00000109', '3', '博士硕士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1153', 'jg00000109', '4', '兼职博士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1154', 'jg00000109', '5', '兼职硕士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1155', 'jg00000109', '6', '兼职博士硕士生导师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1156', 'jg00000109', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1157', 'jg00000110', '1', '20 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1158', 'jg00000110', '2', '21-30 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1159', 'jg00000110', '3', '31-40 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1160', 'jg00000110', '4', '41-50 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1161', 'jg00000110', '5', '51 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1162', 'jg00000111', '1', '文化基础课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1163', 'jg00000111', '2', '专业课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1164', 'jg00000111', '3', '实践技术指导课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1165', 'jg00000111', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1166', 'jg00000112', '10', '教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1167', 'jg00000112', '20', '教师兼行政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1168', 'jg00000112', '21', '教研室主任（组长）', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1169', 'jg00000112', '22', '年级主任（组长）', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1170', 'jg00000112', '23', '班主任', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1171', 'jg00000112', '24', '辅导员、教练员', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1172', 'jg00000112', '25', '共青团工作负责人', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1173', 'jg00000112', '26', '工会工作负责人', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1174', 'jg00000112', '27', '妇女工作负责人', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1175', 'jg00000112', '28', '其他工作负责人', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1176', 'jg00000112', '30', '行政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1177', 'jg00000112', '31', '校领导', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1178', 'jg00000112', '32', '行政处室负责人', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1179', 'jg00000112', '33', '行政处室工作', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1180', 'jg00000112', '34', '行政兼教学工作', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1181', 'jg00000112', '40', '教辅', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1182', 'jg00000112', '41', '实习实验工作与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1183', 'jg00000112', '42', '教学仪器设备维护与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1184', 'jg00000112', '43', '体育设备设施维护与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1185', 'jg00000112', '44', '文艺设备设施维护与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1186', 'jg00000112', '45', '图书教材工作与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1187', 'jg00000112', '46', '档案资料工作与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1188', 'jg00000112', '47', '电教设备维护与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1189', 'jg00000112', '48', '宣传教育工作与管理', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1190', 'jg00000112', '49', '教辅兼教学工作', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1191', 'jg00000112', '60', '工勤', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1192', 'jg00000112', '61', '医护保健工作与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1193', 'jg00000112', '62', '财务统计工作与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1194', 'jg00000112', '63', '校园维护与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1195', 'jg00000112', '64', '建筑维护与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1196', 'jg00000112', '65', '餐饮服务与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1197', 'jg00000112', '66', '生活服务与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1198', 'jg00000112', '67', '商贸服务与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1199', 'jg00000112', '68', '教学服务与管理', '', '', '60', '');
INSERT INTO `uc_dict_item` VALUES ('1200', 'jg00000112', '70', '校办企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1201', 'jg00000112', '71', '校办企业负责人', '', '', '70', '');
INSERT INTO `uc_dict_item` VALUES ('1202', 'jg00000112', '79', '校办企业职工', '', '', '70', '');
INSERT INTO `uc_dict_item` VALUES ('1203', 'jg00000112', '80', '其他工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1204', 'jg00000113', '10', '录用应届毕业生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1205', 'jg00000113', '11', '应届本科生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1206', 'jg00000113', '111', '本校应届本科生', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('1207', 'jg00000113', '112', '外校应届本科生', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('1208', 'jg00000113', '12', '应届硕士生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1209', 'jg00000113', '121', '本校应届硕士生', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('1210', 'jg00000113', '122', '外校应届本科生', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('1211', 'jg00000113', '13', '应届博士生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1212', 'jg00000113', '131', '本校应届博士生', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('1213', 'jg00000113', '132', '外校应届博士生', '', '', '13', '');
INSERT INTO `uc_dict_item` VALUES ('1214', 'jg00000113', '14', '国内博士后', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1215', 'jg00000113', '15', '国外博士/博士后', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1216', 'jg00000113', '16', '其他应届生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1217', 'jg00000113', '20', '军队转业、复员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1218', 'jg00000113', '21', '军转干部安置', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1219', 'jg00000113', '22', '复员军人安置', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1220', 'jg00000113', '30', '调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1221', 'jg00000113', '31', '系统内高校调入', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1222', 'jg00000113', '32', '系统内其他单位调入', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1223', 'jg00000113', '321', '地方教育系统调入', '', '', '32', '');
INSERT INTO `uc_dict_item` VALUES ('1224', 'jg00000113', '33', '系统外调入', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1225', 'jg00000113', '331', '中央其他部门调入', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('1226', 'jg00000113', '332', '地方其他部门调入', '', '', '33', '');
INSERT INTO `uc_dict_item` VALUES ('1227', 'jg00000113', '40', '引进人才', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1228', 'jg00000113', '41', '院士', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1229', 'jg00000113', '42', '特聘教授', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1230', 'jg00000113', '43', '杰出人才', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1231', 'jg00000113', '44', '优秀人才', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1232', 'jg00000113', '45', '录用学成归国人员', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1233', 'jg00000113', '46', '其他引进人才', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1234', 'jg00000113', '49', '外国专家', '', '', '40', '');
INSERT INTO `uc_dict_item` VALUES ('1235', 'jg00000113', '50', '社会招聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1236', 'jg00000113', '51', '社会招聘专业技术人员', '', '', '50', '');
INSERT INTO `uc_dict_item` VALUES ('1237', 'jg00000113', '52', '其他社会招聘人员', '', '', '50', '');
INSERT INTO `uc_dict_item` VALUES ('1238', 'jg00000113', '52', '社会招聘工勤人员', '', '', '50', '');
INSERT INTO `uc_dict_item` VALUES ('1239', 'jg00000113', '99', '其他进校人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1240', 'jg00000114', '1', '4 年及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1241', 'jg00000114', '2', '5-10 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1242', 'jg00000114', '3', '11-20 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1243', 'jg00000114', '4', '21 年及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1244', 'jg00000115', '01', '机械加工类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1245', 'jg00000115', '02', '电工电器工程类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1246', 'jg00000115', '03', '建筑工程类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1247', 'jg00000115', '04', '仪器仪表工程类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1248', 'jg00000115', '05', '水暖通风工程类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1249', 'jg00000115', '06', '机动车驾驶类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1250', 'jg00000115', '07', '家具维修类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1251', 'jg00000115', '08', '宣传广告标本制作类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1252', 'jg00000115', '09', '餐饮服务类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1253', 'jg00000115', '10', '商贸服务类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1254', 'jg00000115', '11', '护理保健类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1255', 'jg00000115', '12', '花木养殖类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1256', 'jg00000115', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1257', 'jg00000116', '111', '录用毕业生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1258', 'jg00000116', '112', '研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1259', 'jg00000116', '113', '本科生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1260', 'jg00000116', '114', '本校研究生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1261', 'jg00000116', '121', '调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1262', 'jg00000116', '122', '外单位教师调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1263', 'jg00000116', '123', '外单位高校教师调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1264', 'jg00000116', '124', '外单位中职教师调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1265', 'jg00000116', '131', '校内外非教师调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1266', 'jg00000116', '132', '本校调整', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1267', 'jg00000116', '133', '增加校内变动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1268', 'jg00000116', '141', '增加其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1269', 'jg00000116', '211', '自然减员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1270', 'jg00000116', '212', '调离教师岗位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1271', 'jg00000116', '213', '调出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1272', 'jg00000116', '214', '减少校内变动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1273', 'jg00000116', '215', '减少其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1274', 'jg00000117', '1', '教学工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1275', 'jg00000117', '2', '科研工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1276', 'jg00000117', '3', '专业技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1277', 'jg00000117', '4', '工作业绩', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1278', 'jg00000117', '5', '科技竞赛', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1279', 'jg00000117', '6', '体育比赛', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1280', 'jg00000117', '7', '文艺比赛', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1281', 'jg00000117', '8', '公益事业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1282', 'jg00000117', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1283', 'jg00000118', '1', '由农村学校向城区学校的流动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1284', 'jg00000118', '2', '城区县直学校之间或农村跨乡镇学校之间的流动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1285', 'jg00000118', '3', '城区学校向农村学校流动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1286', 'jg00000118', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1287', 'jg00000119', '1', '25 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1288', 'jg00000119', '2', '26-30 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1289', 'jg00000119', '3', '30 岁及以下', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1290', 'jg00000119', '4', '31-35 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1291', 'jg00000119', '5', '36-40 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1292', 'jg00000119', '6', '41-45 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1293', 'jg00000119', '7', '46-50 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1294', 'jg00000119', '8', '51-55 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1295', 'jg00000119', '9', '56-60 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1296', 'jg00000119', '10', '61 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1297', 'jg00000119', '11', '61-65 岁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1298', 'jg00000119', '12', '66 岁及以上', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1299', 'jg00000120', '01', '退休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1300', 'jg00000120', '02', '离休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1301', 'jg00000120', '03', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1302', 'jg00000120', '04', '返聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1303', 'jg00000120', '05', '调出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1304', 'jg00000120', '06', '辞职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1305', 'jg00000120', '07', '离职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1306', 'jg00000120', '08', '开除', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1307', 'jg00000120', '09', '下落不明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1308', 'jg00000120', '11', '在职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1309', 'jg00000120', '12', '延聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1310', 'jg00000120', '13', '待退休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1311', 'jg00000120', '14', '长病假', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1312', 'jg00000120', '15', '因公出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1313', 'jg00000120', '16', '停薪留职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1314', 'jg00000120', '17', '待岗', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1315', 'jg00000120', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1316', 'jg00000121', '10', '校本部教职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1317', 'jg00000121', '11', '专任教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1318', 'jg00000121', '12', '教辅人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1319', 'jg00000121', '13', '行政人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1320', 'jg00000121', '14', '工勤人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1321', 'jg00000121', '19', '其他校本部教职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1322', 'jg00000121', '20', '科研机构人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1323', 'jg00000121', '30', '校办企业职工/校办工厂、农（林）场职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1324', 'jg00000121', '40', '其他附设机构人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1325', 'jg00000121', '50', '聘请校外教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1326', 'jg00000121', '51', '来自高校以外科研、事业单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1327', 'jg00000121', '52', '来自校外企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1328', 'jg00000121', '53', '国外聘请', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1329', 'jg00000121', '54', '来自其他高校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1330', 'jg00000121', '55', '代课教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1331', 'jg00000121', '56', '兼任教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1332', 'jg00000121', '59', '其他兼任（职）教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1333', 'jg00000121', '60', '其他人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1334', 'jg00000121', '61', '附属中小学幼儿园教职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1335', 'jg00000121', '62', '集体所有制人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1336', 'jg00000121', '99', '其他教职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1337', 'jg00000122', '10', '录用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1338', 'jg00000122', '11', '城镇应届毕业生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1339', 'jg00000122', '12', '农村应届毕业生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1340', 'jg00000122', '13', '城镇非应届毕业生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1341', 'jg00000122', '14', '农村非应届毕业生', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1342', 'jg00000122', '20', '军队转业、复员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1343', 'jg00000122', '21', '军队转业', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1344', 'jg00000122', '22', '军队复员', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1345', 'jg00000122', '30', '调入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1346', 'jg00000122', '31', '系统内调入', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1347', 'jg00000122', '32', '系统外调入', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1348', 'jg00000122', '40', '回国定居', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1349', 'jg00000122', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1350', 'jg00000123', '1', '出国（境）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1351', 'jg00000123', '2', '国内探亲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1352', 'jg00000123', '3', '事假', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1353', 'jg00000123', '4', '病休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1354', 'jg00000123', '5', '国内进修学习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1355', 'jg00000123', '6', '借调', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1356', 'jg00000123', '7', '不胜任工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1357', 'jg00000123', '8', '处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1358', 'jg00000123', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1359', 'jg00000124', '11', '离休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1360', 'jg00000124', '12', '退休', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1361', 'jg00000124', '21', '系统内调出/调至其他高校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1362', 'jg00000124', '22', '调出至系统外', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1363', 'jg00000124', '23', '调至系统内其他单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1364', 'jg00000124', '31', '参军', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1365', 'jg00000124', '32', '进修学习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1366', 'jg00000124', '41', '辞职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1367', 'jg00000124', '42', '辞退', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1368', 'jg00000124', '43', '合同期满', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1369', 'jg00000124', '51', '除名', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1370', 'jg00000124', '52', '开除', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1371', 'jg00000124', '53', '死亡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1372', 'jg00000124', '54', '失踪', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1373', 'jg00000124', '55', '借调期满', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1374', 'jg00000124', '56', '借调外出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1375', 'jg00000124', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1376', 'jg00000125', '1', '聘任', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1377', 'jg00000125', '11', '已聘', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('1378', 'jg00000125', '12', '高聘', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('1379', 'jg00000125', '13', '低聘', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('1380', 'jg00000125', '2', '不聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1381', 'jg00000125', '3', '缓聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1382', 'jg00000125', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1383', 'jg00000126', '1', '校聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1384', 'jg00000126', '2', '院系聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1385', 'jg00000126', '3', '双聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1386', 'jg00000126', '4', '返聘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1387', 'jg00000126', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1388', 'jg00000127', '1', '主讲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1389', 'jg00000127', '2', '辅导', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1390', 'jg00000127', '3', '实验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1391', 'jg00000127', '4', '习题', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1392', 'jg00000127', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1393', 'jg00000128', '00', '公共基础/通识教育课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1394', 'jg00000128', '01', '政治德育类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1395', 'jg00000128', '02', '人文社科类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1396', 'jg00000128', '03', '经济管理类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1397', 'jg00000128', '04', '自然科学类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1398', 'jg00000128', '05', '外语类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1399', 'jg00000128', '06', '体育类课', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1400', 'jg00000128', '10', '专业课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1401', 'jg00000128', '11', '专业基础课', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1402', 'jg00000128', '12', '专业课', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1403', 'jg00000128', '20', '实践教学课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1404', 'jg00000128', '21', '实验', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1405', 'jg00000128', '22', '实习', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1406', 'jg00000128', '23', '军训', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1407', 'jg00000128', '24', '专业设计', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1408', 'jg00000128', '25', '毕业设计', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1409', 'jg00000128', '30', '研究生课程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1410', 'jg00000128', '31', '学位课', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1411', 'jg00000128', '32', '非学位课', '', '', '30', '');
INSERT INTO `uc_dict_item` VALUES ('1412', 'jg00000128', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1413', 'jg00000129', '1', '学前教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1414', 'jg00000129', '2', '小学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1415', 'jg00000129', '3', '普通初中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1416', 'jg00000129', '4', '普通高中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1417', 'jg00000129', '5', '职业初中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1418', 'jg00000129', '6', '职业高中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1419', 'jg00000129', '7', '成人中等专业学校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1420', 'jg00000129', '8', '成人中学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1421', 'jg00000129', 'A', '特殊教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1422', 'jg00000129', 'Z', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1423', 'jg00000130', '10', '未任课教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1424', 'jg00000130', '11', '进修', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1425', 'jg00000130', '12', '科研', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1426', 'jg00000130', '13', '病休', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1427', 'jg00000130', '19', '其他原因未任课', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1428', 'jg00000130', '20', '任课教师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1429', 'jg00000130', '21', '基础课', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1430', 'jg00000130', '22', '专业课', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1431', 'jg00000130', '23', '实践技术指导', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1432', 'jg00000130', '29', '其他课', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1433', 'jg00000130', '99', '其他不担任教学的教职工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1434', 'jg00000131', '01', '外专局推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1435', 'jg00000131', '02', '政府协议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1436', 'jg00000131', '03', '校际交流', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1437', 'jg00000131', '04', '民间组织推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1438', 'jg00000131', '05', '驻外使领馆', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1439', 'jg00000131', '06', '国内其他组织推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1440', 'jg00000131', '07', '自荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1441', 'jg00000131', '08', '国际组织推荐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1442', 'jg00000131', '09', '友好城市交流', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1443', 'jg00000131', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1444', 'jg00000132', '1', '教科文卫类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1445', 'jg00000132', '2', '经济技术类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1446', 'jg00000133', '1', '主席/主任/组长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1447', 'jg00000133', '2', '副主席/副主任/副组长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1448', 'jg00000133', '3', '委员/成员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1449', 'jg00000133', '4', '秘书长/秘书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1450', 'jg00000134', '10', '院士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1451', 'jg00000134', '11', '中国科学院院士', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1452', 'jg00000134', '12', '中国工程院院士', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1453', 'jg00000134', '13', '两院院士', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1454', 'jg00000134', '14', '外国科学院院士', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1455', 'jg00000134', '16', '中国社会科学院学部委员', '', '', '10', '');
INSERT INTO `uc_dict_item` VALUES ('1456', 'jg00000134', '20', '国家级专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1457', 'jg00000134', '21', '国家主管部门批准的有突出贡献的专家', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1458', 'jg00000134', '22', '享受政府特殊津贴（按月发放）专家', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1459', 'jg00000134', '23', '享受政府特殊津贴（一次性发放）专家', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1460', 'jg00000134', '29', '国家主管部门批准的其他专家', '', '', '20', '');
INSERT INTO `uc_dict_item` VALUES ('1461', 'jg00000134', '30', '省、部级有突出贡献专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1462', 'jg00000134', '40', '地、市级有突出贡献专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1463', 'jg00000134', '50', '县级有突出贡献专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1464', 'jg00000134', '80', '解放军专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1465', 'jg00000134', '81', '全军有突出贡献专家', '', '', '80', '');
INSERT INTO `uc_dict_item` VALUES ('1466', 'jg00000134', '82', '大军区有突出贡献专家', '', '', '80', '');
INSERT INTO `uc_dict_item` VALUES ('1467', 'jg00000134', '83', '军级有突出贡献专家', '', '', '80', '');
INSERT INTO `uc_dict_item` VALUES ('1468', 'jg00000134', '84', '师级有突出贡献专家', '', '', '80', '');
INSERT INTO `uc_dict_item` VALUES ('1469', 'jg00000134', '85', '团级有突出贡献专家', '', '', '80', '');
INSERT INTO `uc_dict_item` VALUES ('1470', 'jg00000134', '99', '其他专家', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1471', 'jg00000135', '11', '党委正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1472', 'jg00000135', '12', '行政正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1473', 'jg00000135', '13', '党的职能部门正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1474', 'jg00000135', '14', '行政职能部门正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1475', 'jg00000135', '15', '民主党派正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1476', 'jg00000135', '16', '社会团体正职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1477', 'jg00000135', '21', '党委副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1478', 'jg00000135', '22', '行政副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1479', 'jg00000135', '23', '党的职能部门副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1480', 'jg00000135', '24', '行政职能部门副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1481', 'jg00000135', '25', '民主党派副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1482', 'jg00000135', '26', '社会团体副职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1483', 'jg00000135', '31', '党委常委', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1484', 'jg00000135', '32', '党委委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1485', 'jg00000135', '33', '纪委委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1486', 'jg00000135', '34', '校长助理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1487', 'jg00000135', '35', '总会计师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1488', 'jg00000135', '36', '总经济师', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1489', 'jg00000135', '38', '党委其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1490', 'jg00000135', '39', '行政其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1491', 'jg00000135', '41', '党的职能部门其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1492', 'jg00000135', '42', '行政职能部门其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1493', 'jg00000135', '43', '民主党派其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1494', 'jg00000135', '44', '社会团体其他职', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1495', 'jg00000136', '01', '常驻国外使馆、领事馆', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1496', 'jg00000136', '02', '常驻国际组织及其代表机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1497', 'jg00000136', '04', '常驻国外其他组织', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1498', 'jg00000136', '05', '短期派驻国外使馆、领事馆', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1499', 'jg00000136', '06', '短期派驻国际组织及其他代表机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1500', 'jg00000136', '08', '短期派驻国外其他组织', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1501', 'jg00000136', '10', '党政代表团出访', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1502', 'jg00000136', '11', '军事代表团出访', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1503', 'jg00000136', '12', '经济贸易和财务方面代表团出访与洽谈', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1504', 'jg00000136', '13', '学术、文艺、体育代表团和其他社会团体出访', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1505', 'jg00000136', '14', '参加国际性的各类比赛', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1506', 'jg00000136', '15', '参加交易会和展览会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1507', 'jg00000136', '20', '引进技术和设备', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1508', 'jg00000136', '21', '商务出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1509', 'jg00000136', '22', '实习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1510', 'jg00000136', '23', '监造', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1511', 'jg00000136', '30', '援外技术工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1512', 'jg00000136', '31', '援建工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1513', 'jg00000136', '32', '援外培训工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1514', 'jg00000136', '33', '劳务出口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1515', 'jg00000136', '34', '合营工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1516', 'jg00000136', '36', '航空、邮电、海运、公路等国际联运业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1517', 'jg00000136', '37', '随船工作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1518', 'jg00000136', '39', '科技合作项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1519', 'jg00000136', '40', '考察', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1520', 'jg00000136', '41', '领奖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1521', 'jg00000136', '42', '参加各种会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1522', 'jg00000136', '43', '进修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1523', 'jg00000136', '44', '讲学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1524', 'jg00000136', '45', '公派留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1525', 'jg00000136', '451', '国家公派博士后', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1526', 'jg00000136', '452', '国家公派博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1527', 'jg00000136', '453', '国家公派硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1528', 'jg00000136', '454', '国家公派本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1529', 'jg00000136', '455', '国家公派进修学习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1530', 'jg00000136', '456', '单位公派博士后', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1531', 'jg00000136', '457', '单位公派博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1532', 'jg00000136', '458', '单位公派硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1533', 'jg00000136', '459', '单位公派本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1534', 'jg00000136', '45A', '单位公派进修学习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1535', 'jg00000136', '46', '自费留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1536', 'jg00000136', '461', '自费博士后', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1537', 'jg00000136', '462', '自费博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1538', 'jg00000136', '463', '自费硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1539', 'jg00000136', '464', '自费本科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1540', 'jg00000136', '465', '自费进修学习', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1541', 'jg00000136', '47', '自费公派留学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1542', 'jg00000136', '60', '旅游', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1543', 'jg00000136', '61', '探亲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1544', 'jg00000136', '62', '会友', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1545', 'jg00000136', '63', '结婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1546', 'jg00000136', '64', '继承财产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1547', 'jg00000136', '65', '就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1548', 'jg00000136', '66', '定居', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1549', 'jg00000136', '70', '特殊任务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1550', 'jg00000136', '80', '换发护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1551', 'jg00000136', '99', '其他原因出国', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1552', 'jg00000137', '01', '香港同胞', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1553', 'jg00000137', '02', '香港同胞亲属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1554', 'jg00000137', '03', '澳门同胞', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1555', 'jg00000137', '04', '澳门同胞亲属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1556', 'jg00000137', '05', '台湾同胞', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1557', 'jg00000137', '06', '台湾同胞亲属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1558', 'jg00000137', '11', '华侨', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1559', 'jg00000137', '12', '侨眷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1560', 'jg00000137', '13', '归侨', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1561', 'jg00000137', '14', '归侨子女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1562', 'jg00000137', '21', '归国留学人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1563', 'jg00000137', '31', '非华裔中国人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1564', 'jg00000137', '41', '外籍华裔人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1565', 'jg00000137', '51', '外国人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1566', 'jg00000137', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1567', 'jg00000138', '1', '高校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1568', 'jg00000138', '2', '研究院（所）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1569', 'jg00000138', '3', '财团', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1570', 'jg00000138', '4', '基金会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1571', 'jg00000138', '5', '民间团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1572', 'jg00000138', '6', '政府组织', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1573', 'jg00000138', '7', '企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1574', 'jg00000138', '8', '新闻机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1575', 'jg00000138', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1576', 'jg00000139', '01', 'HSK 基础 C 级（分数等级：1 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1577', 'jg00000139', '02', 'HSK 基础 B 级（分数等级：2 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1578', 'jg00000139', '03', 'HSK 基础 A 级（分数等级：3 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1579', 'jg00000139', '04', 'HSK 初等 C 级（分数等级：3 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1580', 'jg00000139', '05', 'HSK 初等 B 级（分数等级：4 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1581', 'jg00000139', '06', 'HSK 初等 A 级（分数等级：5 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1582', 'jg00000139', '07', 'HSK 中等 C 级（分数等级：6 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1583', 'jg00000139', '08', 'HSK 中等 B 级（分数等级：7 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1584', 'jg00000139', '09', 'HSK 中等 A 级（分数等级：8 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1585', 'jg00000139', '10', 'HSK 高等 C 级（分数等级：9 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1586', 'jg00000139', '11', 'HSK 高等 B 级（分数等级：10 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1587', 'jg00000139', '12', 'HSK 高等 A 级（分数等级：11 级）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1588', 'jg00000140', '10', '国家级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1589', 'jg00000140', '20', '省、部委级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1590', 'jg00000140', '21', '教育部', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1591', 'jg00000140', '22', '中央其他部委', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1592', 'jg00000140', '23', '省（自治区、直辖市）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1593', 'jg00000140', '30', '省部门级、地（市、州）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1594', 'jg00000140', '31', '省级教育行政部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1595', 'jg00000140', '32', '省级其他部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1596', 'jg00000140', '33', '地（市、州）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1597', 'jg00000140', '40', '地（市、州）部门级、县（区、旗）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1598', 'jg00000140', '41', '地级教育行政部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1599', 'jg00000140', '42', '地级其他部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1600', 'jg00000140', '43', '县级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1601', 'jg00000140', '50', '县部门级、乡（镇）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1602', 'jg00000140', '51', '县级教育行政部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1603', 'jg00000140', '52', '县级其他部门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1604', 'jg00000140', '53', '乡（镇）级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1605', 'jg00000140', '60', '学校级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1606', 'jg00000140', '70', '国际', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1607', 'jg00000140', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1608', 'jg00000141', '1', '特等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1609', 'jg00000141', '2', '一等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1610', 'jg00000141', '3', '二等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1611', 'jg00000141', '4', '三等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1612', 'jg00000141', '5', '四等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1613', 'jg00000141', '6', '未评等级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1614', 'jg00000142', '1', '国家元首', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1615', 'jg00000142', '2', '政府要员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1616', 'jg00000142', '3', '驻华使节', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1617', 'jg00000142', '4', '知名人士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1618', 'jg00000142', '5', '业界首脑', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1619', 'jg00000142', '6', '大学校长', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1620', 'jg00000142', '7', '基金会代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1621', 'jg00000142', '8', '一般人士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1622', 'jg00000142', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1623', 'jg00000143', '1', '主访', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1624', 'jg00000143', '2', '顺访', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1625', 'jg00000144', '1', '一级甲等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1626', 'jg00000144', '2', '一级乙等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1627', 'jg00000144', '3', '二级甲等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1628', 'jg00000144', '4', '二级乙等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1629', 'jg00000144', '5', '三级甲等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1630', 'jg00000144', '6', '三级乙等', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1631', 'jg00000145', '0', '否', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1632', 'jg00000145', '1', '是', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1633', 'jg00000146', '1', '居民身份证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1634', 'jg00000146', '2', '军官证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1635', 'jg00000146', '3', '士兵证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1636', 'jg00000146', '4', '文职干部证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1637', 'jg00000146', '5', '部队离退休证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1638', 'jg00000146', '6', '香港特区护照/身份证明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1639', 'jg00000146', '7', '澳门特区护照/身份证明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1640', 'jg00000146', '8', '台湾居民来往大陆通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1641', 'jg00000146', '9', '境外永久居住证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1642', 'jg00000146', 'A', '护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1643', 'jg00000146', 'B', '户口薄', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1644', 'jg00000146', 'Z', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1645', 'jg00000147', '10', '机关', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1646', 'jg00000147', '11', '省级以上党政机关', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1647', 'jg00000147', '12', '省级以下党政机关', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1648', 'jg00000147', '20', '事业单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1649', 'jg00000147', '21', '科研设计单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1650', 'jg00000147', '22', '高等学校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1651', 'jg00000147', '23', '其他教育单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1652', 'jg00000147', '24', '医疗卫生单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1653', 'jg00000147', '25', '体育文化单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1654', 'jg00000147', '29', '其他事业单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1655', 'jg00000147', '30', '企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1656', 'jg00000147', '31', '国有企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1657', 'jg00000147', '32', '中外合资企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1658', 'jg00000147', '33', '民营（私营）企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1659', 'jg00000147', '34', '外资企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1660', 'jg00000147', '35', '集体企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1661', 'jg00000147', '39', '其他企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1662', 'jg00000147', '40', '部队', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1663', 'jg00000147', '50', '社会组织机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1664', 'jg00000147', '60', '国际组织机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1665', 'jg00000147', '70', '国防科工机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1666', 'jg00000147', '80', '财政金融机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1667', 'jg00000147', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1668', 'jg00000148', '1', '亚洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1669', 'jg00000148', '2', '非洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1670', 'jg00000148', '3', '欧洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1671', 'jg00000148', '4', '北美洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1672', 'jg00000148', '5', '南美洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1673', 'jg00000148', '6', '大洋洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1674', 'jg00000148', '7', '南极洲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1675', 'jg00000149', '0', '未知血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1676', 'jg00000149', '1', 'A 血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1677', 'jg00000149', '2', 'B 血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1678', 'jg00000149', '3', 'AB 血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1679', 'jg00000149', '4', 'O 血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1680', 'jg00000149', '5', 'RH 阳性血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1681', 'jg00000149', '6', 'RH 阴性血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1682', 'jg00000149', '7', 'HLA 血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1683', 'jg00000149', '8', '未定血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1684', 'jg00000149', '9', '其他血型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1685', 'jg00000150', '1', '中央出版社', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1686', 'jg00000150', '2', '地方出版社', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1687', 'jg00000150', '3', '国外（境外）出版社', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1688', 'jg00000151', '01', '科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1689', 'jg00000151', '02', '发明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1690', 'jg00000151', '03', '自然科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1691', 'jg00000151', '04', '哲学社会科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1692', 'jg00000151', '05', '科技进步', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1693', 'jg00000151', '06', '优秀教材', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1694', 'jg00000151', '07', '合理化和技术改造', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1695', 'jg00000151', '08', '技术展览', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1696', 'jg00000151', '09', '星火计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1697', 'jg00000151', '10', '教学成果', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1698', 'jg00000151', '11', '教学软件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1699', 'jg00000151', '12', '实验技术成果', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1700', 'jg00000151', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1701', 'jg00000152', '100', '新产品', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1702', 'jg00000152', '200', '新技术、新工艺', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1703', 'jg00000152', '300', '理论性研究成果', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1704', 'jg00000152', '301', '专著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1705', 'jg00000152', '302', '编著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1706', 'jg00000152', '303', '教材', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1707', 'jg00000152', '304', '译著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1708', 'jg00000152', '305', '工具书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1709', 'jg00000152', '306', '参考书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1710', 'jg00000152', '307', '古籍整理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1711', 'jg00000152', '308', '论文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1712', 'jg00000152', '309', '译文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1713', 'jg00000152', '310', '调查报告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1714', 'jg00000152', '311', '咨询报告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1715', 'jg00000152', '312', '音像软件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1716', 'jg00000152', '400', 'IT 产品', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1717', 'jg00000152', '401', '音像制品（教学课件）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1718', 'jg00000152', '402', 'IT 软件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1719', 'jg00000152', '999', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1720', 'jg00000153', '10', '基础研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1721', 'jg00000153', '20', '应用研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1722', 'jg00000153', '21', '应用基础研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1723', 'jg00000153', '22', '应用技术研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1724', 'jg00000153', '23', '应用基础与应用技术研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1725', 'jg00000153', '24', '应用理论研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1726', 'jg00000153', '30', '试验发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1727', 'jg00000153', '31', '开发研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1728', 'jg00000153', '32', '基本数据积累', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1729', 'jg00000153', '40', 'R＆D 成果应用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1730', 'jg00000153', '50', '其他科技服务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1731', 'jg00000153', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1732', 'jg00000154', '1', '本校独立举办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1733', 'jg00000154', '2', '与外单位合办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1734', 'jg00000154', '3', '主办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1735', 'jg00000154', '4', '协办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1736', 'jg00000154', '5', '承办', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1737', 'jg00000154', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1738', 'jg00000155', '1', '与国外合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1739', 'jg00000155', '2', '与国内高校合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1740', 'jg00000155', '3', '与国内研究机构合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1741', 'jg00000155', '4', '与在华外商独资企业合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1742', 'jg00000155', '5', '与国内其他企业合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1743', 'jg00000155', '6', '独立完成', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1744', 'jg00000155', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1745', 'jg00000156', '11', '国际首创', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1746', 'jg00000156', '12', '国际领先', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1747', 'jg00000156', '13', '国际水平', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1748', 'jg00000156', '14', '国际先进', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1749', 'jg00000156', '21', '国内首创', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1750', 'jg00000156', '22', '国内领先', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1751', 'jg00000156', '23', '国内先进', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1752', 'jg00000156', '31', '能投入生产应用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1753', 'jg00000156', '41', '省（部）先进', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1754', 'jg00000156', '51', '军内先进', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1755', 'jg00000156', '99', '一般水平', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1756', 'jg00000157', '1', '基础研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1757', 'jg00000157', '2', '应用研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1758', 'jg00000157', '3', '试验发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1759', 'jg00000157', '4', '技术开发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1760', 'jg00000157', '5', '应用理论研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1761', 'jg00000157', '6', '综合机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1762', 'jg00000158', '1', '提前完成', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1763', 'jg00000158', '2', '如期执行', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1764', 'jg00000158', '3', '未完成计划', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1765', 'jg00000158', '4', '撤消或未进行', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1766', 'jg00000159', '100', '独立完成人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1767', 'jg00000159', '110', '项目主持人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1768', 'jg00000159', '120', '项目主要负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1769', 'jg00000159', '121', '项目第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1770', 'jg00000159', '122', '项目第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1771', 'jg00000159', '123', '项目第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1772', 'jg00000159', '129', '项目一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1773', 'jg00000159', '130', '项目主要参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1774', 'jg00000159', '131', '项目第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1775', 'jg00000159', '132', '项目第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1776', 'jg00000159', '133', '项目第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1777', 'jg00000159', '136', '项目普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1778', 'jg00000159', '200', '独著人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1779', 'jg00000159', '210', '主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1780', 'jg00000159', '211', '第一主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1781', 'jg00000159', '212', '第二主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1782', 'jg00000159', '213', '第三主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1783', 'jg00000159', '219', '普通主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1784', 'jg00000159', '220', '副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1785', 'jg00000159', '221', '第一副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1786', 'jg00000159', '222', '第二副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1787', 'jg00000159', '223', '第三副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1788', 'jg00000159', '224', '第四副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1789', 'jg00000159', '225', '第五副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1790', 'jg00000159', '229', '普通副主编', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1791', 'jg00000159', '230', '作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1792', 'jg00000159', '231', '第一作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1793', 'jg00000159', '232', '第二作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1794', 'jg00000159', '233', '第三作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1795', 'jg00000159', '234', '第四作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1796', 'jg00000159', '235', '第五作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1797', 'jg00000159', '236', '共同第一作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1798', 'jg00000159', '237', '通讯作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1799', 'jg00000159', '238', '共同通讯作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1800', 'jg00000159', '239', '普通作者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1801', 'jg00000159', '240', '编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1802', 'jg00000159', '241', '第一编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1803', 'jg00000159', '242', '第二编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1804', 'jg00000159', '243', '第三编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1805', 'jg00000159', '244', '第四编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1806', 'jg00000159', '245', '第五编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1807', 'jg00000159', '249', '普通编写者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1808', 'jg00000159', '250', '译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1809', 'jg00000159', '251', '第一译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1810', 'jg00000159', '252', '第二译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1811', 'jg00000159', '253', '第三译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1812', 'jg00000159', '254', '第四译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1813', 'jg00000159', '255', '第五译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1814', 'jg00000159', '259', '其他译者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1815', 'jg00000159', '310', '机构负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1816', 'jg00000159', '311', '机构第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1817', 'jg00000159', '312', '机构第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1818', 'jg00000159', '313', '机构第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1819', 'jg00000159', '319', '机构一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1820', 'jg00000159', '320', '机构参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1821', 'jg00000159', '321', '机构第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1822', 'jg00000159', '322', '机构第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1823', 'jg00000159', '323', '机构第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1824', 'jg00000159', '324', '机构第四参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1825', 'jg00000159', '325', '机构第五参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1826', 'jg00000159', '329', '机构普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1827', 'jg00000159', '410', '获奖成果负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1828', 'jg00000159', '411', '获奖成果第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1829', 'jg00000159', '412', '获奖成果第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1830', 'jg00000159', '413', '获奖成果第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1831', 'jg00000159', '419', '获奖成果一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1832', 'jg00000159', '420', '获奖成果参加人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1833', 'jg00000159', '421', '获奖成果第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1834', 'jg00000159', '422', '获奖成果第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1835', 'jg00000159', '423', '获奖成果第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1836', 'jg00000159', '424', '获奖成果第四参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1837', 'jg00000159', '425', '获奖成果第五参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1838', 'jg00000159', '429', '获奖成果普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1839', 'jg00000159', '510', '专利成果负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1840', 'jg00000159', '511', '专利成果第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1841', 'jg00000159', '512', '专利成果第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1842', 'jg00000159', '513', '专利成果第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1843', 'jg00000159', '519', '专利成果一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1844', 'jg00000159', '520', '专利成果参加人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1845', 'jg00000159', '521', '专利成果第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1846', 'jg00000159', '522', '专利成果第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1847', 'jg00000159', '523', '专利成果第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1848', 'jg00000159', '524', '专利成果第四参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1849', 'jg00000159', '525', '专利成果第五参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1850', 'jg00000159', '529', '专利成果普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1851', 'jg00000159', '610', '鉴定成果负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1852', 'jg00000159', '611', '鉴定成果第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1853', 'jg00000159', '612', '鉴定成果第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1854', 'jg00000159', '613', '鉴定成果第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1855', 'jg00000159', '619', '鉴定成果一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1856', 'jg00000159', '620', '鉴定成果参加人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1857', 'jg00000159', '621', '鉴定成果第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1858', 'jg00000159', '622', '鉴定成果第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1859', 'jg00000159', '623', '鉴定成果第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1860', 'jg00000159', '624', '鉴定成果第四参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1861', 'jg00000159', '625', '鉴定成果第五参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1862', 'jg00000159', '629', '鉴定成果普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1863', 'jg00000159', '710', '转让成果负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1864', 'jg00000159', '711', '转让成果第一负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1865', 'jg00000159', '712', '转让成果第二负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1866', 'jg00000159', '713', '转让成果第三负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1867', 'jg00000159', '719', '转让成果一般负责人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1868', 'jg00000159', '720', '转让成果参加人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1869', 'jg00000159', '721', '转让成果第一参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1870', 'jg00000159', '722', '转让成果第二参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1871', 'jg00000159', '723', '转让成果第三参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1872', 'jg00000159', '724', '转让成果第四参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1873', 'jg00000159', '725', '转让成果第五参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1874', 'jg00000159', '729', '转让成果普通参加者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1875', 'jg00000160', '10', '国际学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1876', 'jg00000160', '11', '国际权威学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1877', 'jg00000160', '12', '国际一般学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1878', 'jg00000160', '19', '国际其他刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1879', 'jg00000160', '20', '全国性学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1880', 'jg00000160', '21', '国内一级、权威、重点学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1881', 'jg00000160', '22', '国内一般学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1882', 'jg00000160', '29', '国内其他刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1883', 'jg00000160', '30', '省级学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1884', 'jg00000160', '31', '省级公开发行学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1885', 'jg00000160', '39', '省级公开发行其他刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1886', 'jg00000160', '40', '院校级学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1887', 'jg00000160', '41', '重点院校学报', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1888', 'jg00000160', '42', '一般院校学报', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1889', 'jg00000160', '49', '院校其他公开发行学术刊物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1890', 'jg00000161', '1', '大会发言', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1891', 'jg00000161', '2', '分会场发言', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1892', 'jg00000161', '3', '海报交流', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1893', 'jg00000161', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1894', 'jg00000162', '10', '著作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1895', 'jg00000162', '11', '专著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1896', 'jg00000162', '12', '编著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1897', 'jg00000162', '13', '译著', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1898', 'jg00000162', '14', '教材', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1899', 'jg00000162', '15', '科普读物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1900', 'jg00000162', '20', '辞典、字典', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1901', 'jg00000162', '21', '手册', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1902', 'jg00000162', '30', '图集', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1903', 'jg00000162', '40', '文艺作品', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1904', 'jg00000162', '41', '作曲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1905', 'jg00000162', '42', '书法', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1906', 'jg00000162', '43', '绘画', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1907', 'jg00000162', '44', '摄影', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1908', 'jg00000162', '45', '工艺美术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1909', 'jg00000162', '49', '其他文艺作品', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1910', 'jg00000162', '50', '报告', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1911', 'jg00000162', '60', '论文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1912', 'jg00000162', '61', '发表论文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1913', 'jg00000162', '62', '会议论文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1914', 'jg00000162', '70', '教学软件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1915', 'jg00000162', '71', '软件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1916', 'jg00000162', '80', '技术标准', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1917', 'jg00000162', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1918', 'jg00000163', '01', '陆地、海洋与大气的开发、估价', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1919', 'jg00000163', '02', '民用宇宙空间', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1920', 'jg00000163', '03', '农业、林业与渔业的发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1921', 'jg00000163', '04', '促进工业的发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1922', 'jg00000163', '05', '能源的生产、储存与分配', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1923', 'jg00000163', '06', '交通、通讯事业的发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1924', 'jg00000163', '07', '教育事业的发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1925', 'jg00000163', '08', '卫生事业的发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1926', 'jg00000163', '09', '社会发展和社会经济服务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1927', 'jg00000163', '10', '环境保护', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1928', 'jg00000163', '11', '知识的全面发展', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1929', 'jg00000163', '12', '其他民用目标', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1930', 'jg00000163', '13', '国防', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1931', 'jg00000164', '1', '国有大中型企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1932', 'jg00000164', '2', '国有小型企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1933', 'jg00000164', '3', '集体企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1934', 'jg00000164', '4', '乡镇企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1935', 'jg00000164', '5', '私营企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1936', 'jg00000164', '6', '三资企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1937', 'jg00000164', '7', '国外（境外）企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1938', 'jg00000164', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1939', 'jg00000165', '1', '独立完成', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1940', 'jg00000165', '2', '有协作单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1941', 'jg00000165', '3', '本单位为协作单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1942', 'jg00000166', '1', '自然科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1943', 'jg00000166', '2', '农业科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1944', 'jg00000166', '3', '医药科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1945', 'jg00000166', '4', '工程与技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1946', 'jg00000166', '5', '人文与社会科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1947', 'jg00000167', '01', '主管部门专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1948', 'jg00000167', '02', '国家发改委、科技部专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1949', 'jg00000167', '04', '国家社科规划、基金项目经费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1950', 'jg00000167', '05', '国家自然科学基金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1951', 'jg00000167', '06', '中央、国家其他部门专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1952', 'jg00000167', '07', '省、市、自治区专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1953', 'jg00000167', '08', '地（市、州）专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1954', 'jg00000167', '09', '县（区、旗）专项费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1955', 'jg00000167', '10', '企、事业单位委托项目经费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1956', 'jg00000167', '11', '各种收入中转为当年研究与发展经费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1957', 'jg00000167', '12', '自筹经费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1958', 'jg00000167', '13', '贷款', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1959', 'jg00000167', '14', '国外资金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1960', 'jg00000167', '15', '港、澳、台地区资金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1961', 'jg00000167', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1962', 'jg00000168', '1', '基础研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1963', 'jg00000168', '2', '应用研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1964', 'jg00000168', '3', '教学研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1965', 'jg00000168', '4', '教材编写', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1966', 'jg00000168', '5', '教育改革试验', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1967', 'jg00000168', '6', '教具标本制作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1968', 'jg00000168', '7', '课件开发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1969', 'jg00000168', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1970', 'jg00000169', '01', '国家“973”项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1971', 'jg00000169', '02', '国家科技攻关项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1972', 'jg00000169', '03', '国家“863”项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1973', 'jg00000169', '04', '国家自然科学基金项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1974', 'jg00000169', '05', '主管部门科技项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1975', 'jg00000169', '06', '国家科技部', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1976', 'jg00000169', '07', '国家发改委', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1977', 'jg00000169', '08', '国家社科规划、基金项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1978', 'jg00000169', '09', '教育部人文、社科规划项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1979', 'jg00000169', '10', '高校古籍整理研究项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1980', 'jg00000169', '11', '中央、国家其他部委项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1981', 'jg00000169', '12', '省、市、自治区项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1982', 'jg00000169', '13', '国际合作项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1983', 'jg00000169', '14', '与港、澳、台合作项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1984', 'jg00000169', '15', '企、事业单位委托', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1985', 'jg00000169', '16', '外资项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1986', 'jg00000169', '17', '地（市、州）项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1987', 'jg00000169', '18', '县（区、旗）项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1988', 'jg00000169', '19', '学校自选项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1989', 'jg00000169', '20', '国防项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1990', 'jg00000169', '90', '非立项', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1991', 'jg00000169', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1992', 'jg00000170', '1', '在研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1993', 'jg00000170', '2', '中止', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1994', 'jg00000170', '3', '延期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1995', 'jg00000170', '4', '结题', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1996', 'jg00000171', '1', '世界性、区域性、国际间学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1997', 'jg00000171', '2', '两国间双边学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1998', 'jg00000171', '3', '全国、地区性学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('1999', 'jg00000171', '4', '省内学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2000', 'jg00000171', '5', '港、澳、台学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2001', 'jg00000171', '6', '校内学术会议', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2002', 'jg00000171', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2003', 'jg00000172', '01', '进修访问学者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2004', 'jg00000172', '02', '攻读博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2005', 'jg00000172', '03', '攻读硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2006', 'jg00000172', '04', '短期讲学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2007', 'jg00000172', '05', '长期任教', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2008', 'jg00000172', '06', '考察', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2009', 'jg00000172', '07', '商谈合作事宜', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2010', 'jg00000172', '08', '校际交流', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2011', 'jg00000172', '09', '合作研究', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2012', 'jg00000172', '10', '出席国际学术会议人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2013', 'jg00000172', '11', '国际学术会议论文交流（未出席）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2014', 'jg00000172', '12', '普通外教', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2015', 'jg00000172', '13', '访问学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2016', 'jg00000172', '14', '一般访问', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2017', 'jg00000172', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2018', 'jg00000173', '1', '国际学术团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2019', 'jg00000173', '2', '国家级学术团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2020', 'jg00000173', '3', '省市、部委级学术团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2021', 'jg00000173', '4', '地（市）级学术团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2022', 'jg00000173', '5', '县（区、旗）级学术团体', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2023', 'jg00000174', '0', '无合作单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2024', 'jg00000174', '1', '高校', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2025', 'jg00000174', '2', '研究院(所)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2026', 'jg00000174', '3', '企业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2027', 'jg00000174', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2028', 'jg00000175', '1', '授权专利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2029', 'jg00000175', '2', '待批专利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2030', 'jg00000175', '3', '撤销专利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2031', 'jg00000175', '4', '无效专利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2032', 'jg00000175', '5', '终止专利', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2033', 'jg00000176', '1', '发明', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2034', 'jg00000176', '2', '实用新型', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2035', 'jg00000176', '3', '外观设计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2036', 'jg00000176', '4', 'PCT 或外国申请', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2037', 'jg00000177', '0', '未批准', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2038', 'jg00000177', '1', '国内', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2039', 'jg00000177', '2', '国外', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2040', 'jg00000177', '3', '国内、外', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2041', 'jg00000178', '1', '学校独立产权', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2042', 'jg00000178', '2', '产权非学校但独立使用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2043', 'jg00000178', '3', '产权非属学校共同使用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2044', 'jg00000179', '1', '卫生厕所', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2045', 'jg00000179', '2', '非卫生厕所', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2046', 'jg00000179', '3', '无厕所', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2047', 'jg00000180', 'A', '甲级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2048', 'jg00000180', 'B', '乙级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2049', 'jg00000180', 'C', '丙级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2050', 'jg00000180', 'D', '无', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2051', 'jg00000181', '1', '本校使用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2052', 'jg00000181', '2', '外单位租赁', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2053', 'jg00000181', '3', '空闲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2054', 'jg00000181', '4', '不能使用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2055', 'jg00000182', '1', '教学及辅助用房', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2056', 'jg00000182', '111', '教室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2057', 'jg00000182', '112', '专用教室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2058', 'jg00000182', '113', '活动室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2059', 'jg00000182', '114', '卫生间', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2060', 'jg00000182', '115', '睡眠室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2061', 'jg00000182', '116', '保健室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2062', 'jg00000182', '117', '卫生室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2063', 'jg00000182', '121', '实验室、实习场所', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2064', 'jg00000182', '122', '专用科研用房', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2065', 'jg00000182', '123', '计算机房', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2066', 'jg00000182', '124', '语音室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2067', 'jg00000182', '131', '图书馆', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2068', 'jg00000182', '132', '图书室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2069', 'jg00000182', '141', '体育馆/体育活动室', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2070', 'jg00000182', '151', '会堂', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2071', 'jg00000182', '199', '其他教学及辅助用房', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2072', 'jg00000182', '2', '行政办公用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2073', 'jg00000182', '211', '教师办公室', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2074', 'jg00000182', '299', '其他行政办公用房', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2075', 'jg00000182', '3', '生活用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2076', 'jg00000182', '311', '学生宿舍（公寓）', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2077', 'jg00000182', '312', '教工宿舍', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2078', 'jg00000182', '313', '教工周转宿舍', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2079', 'jg00000182', '321', '食堂', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2080', 'jg00000182', '322', '学生食堂', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2081', 'jg00000182', '323', '教工食堂', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2082', 'jg00000182', '324', '厨房', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2083', 'jg00000182', '331', '厕所', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2084', 'jg00000182', '332', '浴室', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2085', 'jg00000182', '333', '锅炉房', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2086', 'jg00000182', '341', '生活福利及附属用房', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2087', 'jg00000182', '399', '其他生活用房', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2088', 'jg00000182', '4', '教工住宅', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2089', 'jg00000182', '9', '其他用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2090', 'jg00000183', '01', '自购福利住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2091', 'jg00000183', '02', '自购经济实用住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2092', 'jg00000183', '03', '自购商品住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2093', 'jg00000183', '04', '自建住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2094', 'jg00000183', '05', '租住本校公有住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2095', 'jg00000183', '06', '租住外单位公有住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2096', 'jg00000183', '07', '租住私有住房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2097', 'jg00000183', '08', '集体宿舍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2098', 'jg00000183', '09', '本校暂借', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2099', 'jg00000183', '10', '外单位暂借', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2100', 'jg00000183', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2101', 'jg00000184', '1', '楼房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2102', 'jg00000184', '2', '平房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2103', 'jg00000184', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2104', 'jg00000185', '1', '国家级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2105', 'jg00000185', '2', '国家重点', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2106', 'jg00000185', '3', '省部级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2107', 'jg00000185', '4', '学校级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2108', 'jg00000185', '5', '院系级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2109', 'jg00000185', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2110', 'jg00000186', '1', '集中供热', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2111', 'jg00000186', '2', '采暖锅炉', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2112', 'jg00000186', '3', '地热', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2113', 'jg00000186', '4', '火炉', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2114', 'jg00000186', '5', '热风', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2115', 'jg00000186', '6', '其他形式', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2116', 'jg00000186', '7', '无采暖', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2117', 'jg00000187', '1', '自备水源', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2118', 'jg00000187', '2', '网管供水', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2119', 'jg00000187', '3', '无水源', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2120', 'jg00000188', '01', '预订', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2121', 'jg00000188', '02', '邮购', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2122', 'jg00000188', '03', '外采', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2123', 'jg00000188', '04', '增选', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2124', 'jg00000188', '05', '交换', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2125', 'jg00000188', '06', '复制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2126', 'jg00000188', '07', '征集', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2127', 'jg00000188', '08', '访求', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2128', 'jg00000188', '9', '赠送', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2129', 'jg00000188', '10', '调拨', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2130', 'jg00000188', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2131', 'jg00000189', 'A', '甲级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2132', 'jg00000189', 'B', '乙级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2133', 'jg00000189', 'C', '丙级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2134', 'jg00000189', 'D', '无', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2135', 'jg00000190', '1', '安全', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2136', 'jg00000190', '2', '存在一般安全隐患', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2137', 'jg00000190', '3', '存在重大安全隐患', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2138', 'jg00000191', '1', '房屋建筑', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2139', 'jg00000191', '2', '围墙', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2140', 'jg00000191', '3', '校门', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2141', 'jg00000191', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2142', 'jg00000192', '11', '浅基础：条形基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2143', 'jg00000192', '12', '浅基础：筏板基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2144', 'jg00000192', '13', '浅基础：独立基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2145', 'jg00000192', '14', '浅基础：其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2146', 'jg00000192', '21', '深基础：桩基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2147', 'jg00000192', '22', '深基础：箱型基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2148', 'jg00000192', '23', '深基础：其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2149', 'jg00000192', '31', '其他基础形式', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2150', 'jg00000192', '41', '无基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2151', 'jg00000193', '1', '抗震鉴定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2152', 'jg00000193', '2', '安全鉴定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2153', 'jg00000193', '3', '抗淹没抗洪水冲击鉴定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2154', 'jg00000193', '4', '抗风能力验算', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2155', 'jg00000193', '5', '其他鉴定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2156', 'jg00000194', '1', '土木结构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2157', 'jg00000194', '2', '砖木结构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2158', 'jg00000194', '3', '砖混结构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2159', 'jg00000194', '4', '框架结构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2160', 'jg00000194', '5', '钢结构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2161', 'jg00000194', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2162', 'jg00000195', '1', '预制', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2163', 'jg00000195', '2', '现浇', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2164', 'jg00000195', '3', '木板', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2165', 'jg00000195', '4', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2166', 'jg00000196', '1', '走道式组合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2167', 'jg00000196', '2', '套件式组合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2168', 'jg00000196', '3', '大厅式组合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2169', 'jg00000196', '4', '单元式组合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2170', 'jg00000196', '5', '混合式组合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2171', 'jg00000197', '10', '教学及辅助用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2172', 'jg00000197', '11', '教学楼', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2173', 'jg00000197', '12', '综合楼', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2174', 'jg00000197', '13', '实验楼', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2175', 'jg00000197', '14', '图书馆(室)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2176', 'jg00000197', '15', '体育活动室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2177', 'jg00000197', '16', '礼堂', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2178', 'jg00000197', '20', '生活用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2179', 'jg00000197', '21', '学生宿舍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2180', 'jg00000197', '22', '食堂', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2181', 'jg00000197', '23', '厕所', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2182', 'jg00000197', '24', '锅炉房(开水房)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2183', 'jg00000197', '25', '浴室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2184', 'jg00000197', '26', '教工宿舍', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2185', 'jg00000197', '30', '行政办公用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2186', 'jg00000197', '31', '办公楼', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2187', 'jg00000197', '32', '卫生保健室', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2188', 'jg00000197', '40', '其他用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2189', 'jg00000197', '41', '其他用房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2190', 'jg00000197', '51', '其他配套设施', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2191', 'jg00000198', '10', '正常', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2192', 'jg00000198', '20', '危房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2193', 'jg00000198', '21', 'a 级危房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2194', 'jg00000198', '22', 'b 级危房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2195', 'jg00000198', '23', 'c 级危房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2196', 'jg00000198', '24', 'd 级危房', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2197', 'jg00000198', '30', '正在施工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2198', 'jg00000198', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2199', 'jg00000199', '1', '平装', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2200', 'jg00000199', '2', '精装', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2201', 'jg00000199', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2202', 'jg00000200', '1', '特殊设防类', '', '', '0', '甲类');
INSERT INTO `uc_dict_item` VALUES ('2203', 'jg00000200', '2', '重点设防类', '', '', '0', '乙类');
INSERT INTO `uc_dict_item` VALUES ('2204', 'jg00000200', '3', '标准设防类', '', '', '0', '丙类');
INSERT INTO `uc_dict_item` VALUES ('2205', 'jg00000200', '4', '适度设防类', '', '', '0', '丁类');
INSERT INTO `uc_dict_item` VALUES ('2206', 'jg00000201', '1', '5度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2207', 'jg00000201', '2', '6度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2208', 'jg00000201', '3', '7度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2209', 'jg00000201', '4', '7.5 度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2210', 'jg00000201', '5', '8度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2211', 'jg00000201', '6', '8.5 度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2212', 'jg00000201', '9', '9度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2213', 'jg00000202', '1', '购置', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2214', 'jg00000202', '2', '调剂', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2215', 'jg00000202', '3', '接受捐赠', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2216', 'jg00000202', '4', '置换', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2217', 'jg00000202', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2218', 'jg00000203', '1', '特级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2219', 'jg00000203', '2', '一级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2220', 'jg00000203', '3', '二级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2221', 'jg00000203', '4', '三级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2222', 'jg00000203', '5', '无', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2223', 'jg00000204', 'A', '一类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2224', 'jg00000204', 'B', '二类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2225', 'jg00000204', 'C', '三类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2226', 'jg00000204', 'D', '无', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2227', 'jg00000205', 'A', '一类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2228', 'jg00000205', 'B', '二类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2229', 'jg00000205', 'C', '三类', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2230', 'jg00000205', 'D', '无', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2231', 'jg00000206', '1', '设备购置费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2232', 'jg00000206', '2', '基建费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2233', 'jg00000206', '3', '运行费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2234', 'jg00000206', '4', '教改费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2235', 'jg00000206', '5', '水费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2236', 'jg00000206', '6', '电费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2237', 'jg00000206', '7', '维护费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2238', 'jg00000206', '8', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2239', 'jg00000207', '1', '基础、专业基础或技术基础', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2240', 'jg00000207', '2', '专业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2241', 'jg00000207', '3', '科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2242', 'jg00000207', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2243', 'jg00000208', '11', '国家级评估为 A', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2244', 'jg00000208', '12', '国家级评估为 B', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2245', 'jg00000208', '13', '国家级评估为 C', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2246', 'jg00000208', '14', '国家级评估为 D', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2247', 'jg00000208', '20', '省部委级评估不合格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2248', 'jg00000208', '21', '省部委级评估合格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2249', 'jg00000208', '30', '校级评估不合格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2250', 'jg00000208', '31', '校级评估合格', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2251', 'jg00000209', '1', '使用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2252', 'jg00000209', '2', '闲置', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2253', 'jg00000209', '3', '被借用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2254', 'jg00000209', '4', '借用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2255', 'jg00000209', '5', '租用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2256', 'jg00000209', '6', '出租', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2257', 'jg00000209', '7', '对外投资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2258', 'jg00000209', '8', '担保', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2259', 'jg00000210', '1', '在借', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2260', 'jg00000210', '2', '在库', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2261', 'jg00000210', '3', '挂失', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2262', 'jg00000210', '4', '残缺', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2263', 'jg00000210', '5', '报废', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2264', 'jg00000210', '6', '已订未到货', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2265', 'jg00000210', '7', '待编目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2266', 'jg00000210', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2267', 'jg00000211', 'A', '全国重点文物保护单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2268', 'jg00000211', 'B', '省级文物保护单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2269', 'jg00000211', 'C', '市县级文物保护单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2270', 'jg00000211', 'D', '尚未核定公布单位的文物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2271', 'jg00000211', 'X', '非文物建筑', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2272', 'jg00000212', '1', '一级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2273', 'jg00000212', '2', '二级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2274', 'jg00000212', '3', '三级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2275', 'jg00000212', '4', '四级', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2276', 'jg00000213', '1', '教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2277', 'jg00000213', '2', '科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2278', 'jg00000213', '3', '行政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2279', 'jg00000213', '4', '生活与后勤', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2280', 'jg00000213', '5', '生产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2281', 'jg00000213', '6', '技术开发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2282', 'jg00000213', '7', '社会服务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2283', 'jg00000213', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2284', 'jg00000214', '1', '在用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2285', 'jg00000214', '2', '闲置', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2286', 'jg00000214', '3', '待修', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2287', 'jg00000214', '4', '待报废', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2288', 'jg00000214', '5', '丢失', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2289', 'jg00000214', '6', '报废', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2290', 'jg00000214', '7', '调出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2291', 'jg00000214', '8', '降档', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2292', 'jg00000214', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2293', 'jg00000215', '1', '房屋用地', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2294', 'jg00000215', '2', '体育场地', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2295', 'jg00000215', '3', '绿化用地', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2296', 'jg00000215', '4', '农、林场用地', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2297', 'jg00000215', '9', '其他用地', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2298', 'jg00000216', '1', '公开招标', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2299', 'jg00000216', '2', '邀请招标', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2300', 'jg00000216', '3', '无招标', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2301', 'jg00000217', 'A', '义教工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2302', 'jg00000217', 'B', '校舍维修改造工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2303', 'jg00000217', 'C', '危房改造工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2304', 'jg00000217', 'D', '寄宿制工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2305', 'jg00000217', 'E', '世行贷款工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2306', 'jg00000217', 'F', '西扶项目工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2307', 'jg00000217', 'G', '抗震救灾工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2308', 'jg00000217', 'H', '初中校舍改造工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2309', 'jg00000217', 'I', '特殊教育学校建设工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2310', 'jg00000217', 'J', '布局调整项目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2311', 'jg00000217', 'K', '新农村卫生新校园建设工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2312', 'jg00000217', 'L', '边境工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2313', 'jg00000217', 'M', '安全工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2314', 'jg00000217', 'N', '薄弱项目改造工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2315', 'jg00000217', 'Z', '中央其他工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2316', 'jg00000218', '1', '统一票据', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2317', 'jg00000218', '2', '银钱收据', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2318', 'jg00000218', '3', '捐赠收据', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2319', 'jg00000218', '4', '校内收据', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2320', 'jg00000218', '5', '资金往来收据', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2321', 'jg00000218', '6', '服务业发票', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2322', 'jg00000219', '1', '一借多贷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2323', 'jg00000219', '2', '一贷多借', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2324', 'jg00000219', '3', '多贷多借', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2325', 'jg00000220', '1', '卷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2326', 'jg00000220', '2', '本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2327', 'jg00000221', '1', '教育事业费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2328', 'jg00000221', '2', '科研专款及基金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2329', 'jg00000221', '3', '基建设备费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2330', 'jg00000221', '4', '自筹经费', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2331', 'jg00000221', '5', '世界银行贷款', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2332', 'jg00000221', '6', '捐款', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2333', 'jg00000221', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2334', 'jg00000222', '1', '财政拨款', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2335', 'jg00000222', '2', '贷款', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2336', 'jg00000222', '3', '自筹资金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2337', 'jg00000222', '4', '捐助', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2338', 'jg00000222', '5', '集资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2339', 'jg00000222', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2340', 'jg00000223', '1', '借方', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2341', 'jg00000223', '2', '贷方', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2342', 'jg00000224', '0', '总分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2343', 'jg00000224', '1', '一级分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2344', 'jg00000224', '2', '二级分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2345', 'jg00000224', '3', '三级分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2346', 'jg00000224', '4', '四级分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2347', 'jg00000224', '5', '五级分类科目', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2348', 'jg00000225', '1', '现金', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2349', 'jg00000225', '2', '银行', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2350', 'jg00000225', '3', '暂付', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2351', 'jg00000225', '4', '暂存', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2352', 'jg00000225', '5', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2353', 'jg00000226', '1', '资产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2354', 'jg00000226', '2', '负债', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2355', 'jg00000226', '3', '净资产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2356', 'jg00000226', '4', '收入', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2357', 'jg00000226', '5', '支出', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2358', 'jg00000226', '6', '预算', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2359', 'jg00000227', '1', '永久', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2360', 'jg00000227', '2', '长期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2361', 'jg00000227', '21', '30 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2362', 'jg00000227', '22', '25 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2363', 'jg00000227', '3', '短期', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2364', 'jg00000227', '31', '15 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2365', 'jg00000227', '32', '10 年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2366', 'jg00000227', '33', '5年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2367', 'jg00000227', '34', '3年', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2368', 'jg00000228', '1', '草稿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2369', 'jg00000228', '2', '定稿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2370', 'jg00000228', '3', '正本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2371', 'jg00000228', '4', '副本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2372', 'jg00000228', '5', '试行本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2373', 'jg00000228', '6', '修订本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2374', 'jg00000228', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2375', 'jg00000229', '01', '转发/被转发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2376', 'jg00000229', '02', '来文/复文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2377', 'jg00000229', '03', '正文/附件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2378', 'jg00000229', '04', '新版本/旧版本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2379', 'jg00000229', '05', '包含/被包含', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2380', 'jg00000229', '06', '前/后', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2381', 'jg00000229', '07', '替代/被替代', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2382', 'jg00000229', '08', '参考/被参考', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2383', 'jg00000229', '09', '参见/被参见', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2384', 'jg00000229', '10', '引用/被引用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2385', 'jg00000229', '11', '操控/被操控', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2386', 'jg00000229', '12', '完成/被完成', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2387', 'jg00000229', '13', '形成/被形成', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2388', 'jg00000229', '14', '隶属/被隶属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2389', 'jg00000230', '01', '文件—文件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2390', 'jg00000230', '02', '文件—案卷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2391', 'jg00000230', '03', '案卷—文件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2392', 'jg00000230', '04', '案卷—案卷', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2393', 'jg00000230', '05', '文件—单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2394', 'jg00000230', '06', '文件—内设机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2395', 'jg00000230', '07', '文件—个人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2396', 'jg00000230', '08', '案卷—单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2397', 'jg00000230', '09', '案卷—内设机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2398', 'jg00000230', '10', '案卷—个人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2399', 'jg00000230', '11', '文档—单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2400', 'jg00000230', '12', '文档—内设机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2401', 'jg00000230', '13', '文档—个人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2402', 'jg00000230', '14', '文件—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2403', 'jg00000230', '15', '案卷—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2404', 'jg00000230', '16', '文档—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2405', 'jg00000230', '17', '单位—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2406', 'jg00000230', '18', '内设机构—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2407', 'jg00000230', '19', '个人—业务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2408', 'jg00000230', '20', '文档—文档', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2409', 'jg00000230', '21', '文档—文件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2410', 'jg00000230', '22', '文件—文档', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2411', 'jg00000230', '23', '个人—内设机构', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2412', 'jg00000230', '24', '内设机构—单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2413', 'jg00000230', '25', '个人—单位', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2414', 'jg00000230', '26', '业务—文件—机构人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2415', 'jg00000230', '27', '业务—案卷—机构人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2416', 'jg00000230', '28', '业务—文档—机构人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2417', 'jg00000231', '1', '单件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2418', 'jg00000231', '2', '组合文件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2419', 'jg00000232', '10', '收集', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2420', 'jg00000232', '20', '鉴定', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2421', 'jg00000232', '30', '整理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2422', 'jg00000232', '40', '保管', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2423', 'jg00000232', '50', '检索', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2424', 'jg00000232', '60', '编研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2425', 'jg00000232', '70', '利用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2426', 'jg00000232', '80', '统计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2427', 'jg00000233', '01', '文本', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2428', 'jg00000233', '02', '图像', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2429', 'jg00000233', '03', '图形', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2430', 'jg00000233', '04', '声音', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2431', 'jg00000233', '05', '影像', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2432', 'jg00000233', '06', '超媒体链接', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2433', 'jg00000233', '07', '程序', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2434', 'jg00000233', '08', '数据文件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2435', 'jg00000233', '09', '动画', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2436', 'jg00000233', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2437', 'jg00000234', '1', '印送', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2438', 'jg00000234', '2', '传抄', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2439', 'jg00000234', '3', '网络', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2440', 'jg00000234', '4', '传真', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2441', 'jg00000234', '5', '电话', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2442', 'jg00000234', '6', '电报', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2443', 'jg00000234', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2444', 'jg00000235', '01', '学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2445', 'jg00000235', '02', '招生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2446', 'jg00000235', '03', '教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2447', 'jg00000235', '04', '就业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2448', 'jg00000235', '05', '科研', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2449', 'jg00000235', '06', '人事', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2450', 'jg00000235', '07', '外事', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2451', 'jg00000235', '08', '财务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2452', 'jg00000235', '09', '后勤', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2453', 'jg00000235', '10', '房产', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2454', 'jg00000235', '11', '设备', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2455', 'jg00000235', '12', '伙食', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2456', 'jg00000235', '13', '行政', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2457', 'jg00000235', '14', '党务', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2458', 'jg00000235', '15', '宣传', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2459', 'jg00000235', '16', '纪检', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2460', 'jg00000235', '17', '统战', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2461', 'jg00000235', '18', '保卫', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2462', 'jg00000235', '19', '统计', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2463', 'jg00000235', '20', '产业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2464', 'jg00000235', '21', '信息网络', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2465', 'jg00000235', '22', '校园环境', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2466', 'jg00000235', '98', '综合', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2467', 'jg00000235', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2468', 'jg00000236', '1', '主动公开', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2469', 'jg00000236', '2', '依申请公开', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2470', 'jg00000236', '3', '不予公开', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2471', 'jg00000237', '1', '粘封', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2472', 'jg00000237', '2', '缝封', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2473', 'jg00000237', '3', '轧封', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2474', 'jg00000237', '4', '捆轧', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2475', 'jg00000237', '5', '印存', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2476', 'jg00000237', '6', '纸封', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2477', 'jg00000237', '7', '铅封', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2478', 'jg00000237', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2479', 'jg00000238', '1', '待签收', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2480', 'jg00000238', '2', '已签收', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2481', 'jg00000238', '3', '已拒收', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2482', 'jg00000238', '4', '审批中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2483', 'jg00000238', '5', '已审批', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2484', 'jg00000238', '6', '待签发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2485', 'jg00000238', '7', '已签发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2486', 'jg00000238', '8', '处理中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2487', 'jg00000238', '9', '已办结', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2488', 'jg00000239', '1', '特提', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2489', 'jg00000239', '2', '特急', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2490', 'jg00000239', '3', '加急', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2491', 'jg00000239', '4', '平急', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2492', 'jg00000239', '5', '急件', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2493', 'jg00000239', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2494', 'jg00000240', '1', '整理中', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2495', 'jg00000240', '2', '待审批', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2496', 'jg00000240', '3', '已分发', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2497', 'jg00000241', '1', '黑白', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2498', 'jg00000241', '2', '灰度', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2499', 'jg00000241', '3', '彩色', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2500', 'jg00000242', '01', '决议', '', '', '0', '党的机关公文，适用于经会议讨论通过的重要决策事项');
INSERT INTO `uc_dict_item` VALUES ('2501', 'jg00000242', '02', '决定', '', '', '0', '适用于对重要事项或重大行动做出安排，奖惩有关单位及人员，变更或者撤销下级机关不适当的决定事项。');
INSERT INTO `uc_dict_item` VALUES ('2502', 'jg00000242', '03', '指示', '', '', '0', '党的机关公文，适用于对下级机关布置工作，提出开展工作的原则和要求');
INSERT INTO `uc_dict_item` VALUES ('2503', 'jg00000242', '04', '意见', '', '', '0', '适用于对重要问题提出见解和处理办法。');
INSERT INTO `uc_dict_item` VALUES ('2504', 'jg00000242', '05', '通知', '', '', '0', '适用于批转下级机关的公文，转发上级机关和不相隶属机关的公文，传达要求下级机关');
INSERT INTO `uc_dict_item` VALUES ('2505', 'jg00000242', '06', '通报', '', '', '0', '办理和需要有关单位周知或者执行的事项，任免人员。');
INSERT INTO `uc_dict_item` VALUES ('2506', 'jg00000242', '07', '公告', '', '', '0', '适用于表彰先进，批评错误，传达重要精神或者情况。');
INSERT INTO `uc_dict_item` VALUES ('2507', 'jg00000242', '08', '报告', '', '', '0', '适用于向国内外宣布重要事项或者法定事项。');
INSERT INTO `uc_dict_item` VALUES ('2508', 'jg00000242', '09', '请示', '', '', '0', '适用于向上级机关汇报工作，反映情况，答复上级机关的询问。');
INSERT INTO `uc_dict_item` VALUES ('2509', 'jg00000242', '10', '批复', '', '', '0', '下级机关请求上级机关给于指示或批准的一种公文。');
INSERT INTO `uc_dict_item` VALUES ('2510', 'jg00000242', '11', '条例', '', '', '0', '适用于答复下级机关请示事项。');
INSERT INTO `uc_dict_item` VALUES ('2511', 'jg00000242', '12', '规定', '', '', '0', '适用于党的中央组织制定规范党组织的工作、活动和党员行为的规章制度党的机关公文，适用于特定范围内的工作和事务制定具有约束力的行为规范');
INSERT INTO `uc_dict_item` VALUES ('2512', 'jg00000242', '13', '会议纪要', '', '', '0', '适用于记载、传达会议情况和议定事项。');
INSERT INTO `uc_dict_item` VALUES ('2513', 'jg00000242', '14', '简报', '', '', '0', '向本单位领导和上级机关以反映情况为主的内部工作报告，也是向有关方面提出问题，反映动态，表扬先进，交流经验的一种重要工具。');
INSERT INTO `uc_dict_item` VALUES ('2514', 'jg00000242', '15', '函', '', '', '0', '指公务信函和公务复函这种正式公文，适用于不相隶属机关之间相互商洽工作、询问和答复问题，请求批准和答复审批事项。');
INSERT INTO `uc_dict_item` VALUES ('2515', 'jg00000242', '16', '通告', '', '', '0', '适用于公布各有关方面应当遵守或者周知的事项。');
INSERT INTO `uc_dict_item` VALUES ('2516', 'jg00000242', '17', '对外报文', '', '', '0', '国家行政机关及其领导人发布的指挥性和强制性的公文。适用于依照有关法律公布行政法规和规章；宣布施行重大强制性行政措施；嘉奖有关单位及人员，撤销下级机关不适当的决定。');
INSERT INTO `uc_dict_item` VALUES ('2517', 'jg00000242', '18', '命令', '', '', '0', '全国人民代表大会常务委员会委员长、中华人民共和国主席、国务院总理、各部部长、各委员会主任可以发布命令。');
INSERT INTO `uc_dict_item` VALUES ('2518', 'jg00000242', '19', '公报', '', '', '0', '公报也称新闻公报，是党政机关和人民团体公开发布重大事件或重要决定事项的报道性公文。');
INSERT INTO `uc_dict_item` VALUES ('2519', 'jg00000242', '20', '议案', '', '', '0', '议案是由具有法定提案权的国家机关、会议常设或临时设立的机构和组织，以及一定数量的个人，向权利机构提出进行审议并作出决定的议事原案。每个国家的议案提交程序和规定都是不一样的，但是都是行使国家权利的重要手');
INSERT INTO `uc_dict_item` VALUES ('2520', 'jg00000243', '1', '阅文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2521', 'jg00000243', '2', '拟办', '', '', '0', '公文拟办，就是对机关收文收电提出如何处理或由谁处理的初步意见，供领导同志批办');
INSERT INTO `uc_dict_item` VALUES ('2522', 'jg00000243', '3', '批办', '', '', '0', '批办是一项由法定或特定责任者履行事务处置权的决策活动。批办公文时，必须明确公文的处置方法、程序、批办责任、批办原则和要求');
INSERT INTO `uc_dict_item` VALUES ('2523', 'jg00000243', '4', '承办', '', '', '0', '公文承办就是主管部门对需要办理的公文进行办理');
INSERT INTO `uc_dict_item` VALUES ('2524', 'jg00000243', '5', '催办', '', '', '0', '公文催办. 是指对公文承办工作的督促和检查');
INSERT INTO `uc_dict_item` VALUES ('2525', 'jg00000243', '6', '办复', '', '', '0', '办复是在办理完毕后，将承办情况和结果及时向批示领导人和来文单位答复');
INSERT INTO `uc_dict_item` VALUES ('2526', 'jg00000243', '7', '注办', '', '', '0', '注办的主要作用是备忘待查，为日后查考了解某一公文的承办过程、承办方式、承办结果提供依据,注办由承办人随手完成');
INSERT INTO `uc_dict_item` VALUES ('2527', 'jg00000243', '8', '暂存', '', '', '0', '公文的暂存，是指对既不应立卷归档或清退，又暂不宜销毁的公文，仍需再留存一定时期以备查用');
INSERT INTO `uc_dict_item` VALUES ('2528', 'jg00000244', '1', '待审批', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2529', 'jg00000244', '2', '已上会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2530', 'jg00000244', '3', '已结题', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2531', 'jg00000245', '1', '纸质', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2532', 'jg00000245', '2', '磁盘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2533', 'jg00000245', '3', '光盘', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2534', 'jg00000245', '4', '磁带', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2535', 'jg00000245', '5', '闪存', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2536', 'jg00000245', '6', '胶片', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2537', 'jg00000245', '9', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2538', '', '', '', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2539', 'gb00000001', '0', '未知的性别', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2540', 'gb00000001', '1', '男性', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2541', 'gb00000001', '2', '女性', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2542', 'gb00000001', '9', '未说明的性别', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2543', 'gb00000002', '10', '未婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2544', 'gb00000002', '20', '已婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2545', 'gb00000002', '21', '初婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2546', 'gb00000002', '22', '再婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2547', 'gb00000002', '23', '复婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2548', 'gb00000002', '30', '丧偶', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2549', 'gb00000002', '40', '离婚', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2550', 'gb00000002', '90', '未说明的婚姻状况', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2551', 'gb00000003', '1', '健康或良好', '', '', '0', '人体生理机能、营养、发育状况良好');
INSERT INTO `uc_dict_item` VALUES ('2552', 'gb00000003', '2', '一般或较弱', '', '', '0', '人体生理机能、营养、发育状况正常，但身体体质较弱有慢性疾病');
INSERT INTO `uc_dict_item` VALUES ('2553', 'gb00000003', '3', '有慢性病', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2554', 'gb00000003', '6', '残疾', '', '', '0', '心理、生理、人体结构上，某种组织、功能丧失或不正常，全部或部分丧失以正常方式从事某种活动的人');
INSERT INTO `uc_dict_item` VALUES ('2555', 'gb00000003', '10', '健康或良好', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2556', 'gb00000003', '20', '一般或较弱', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2557', 'gb00000003', '30', '有慢性病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2558', 'gb00000003', '31', '心血管病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2559', 'gb00000003', '32', '脑血管病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2560', 'gb00000003', '33', '慢性呼吸系统病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2561', 'gb00000003', '34', '慢性消化系统病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2562', 'gb00000003', '35', '慢性肾炎', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2563', 'gb00000003', '36', '结核病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2564', 'gb00000003', '37', '糖尿病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2565', 'gb00000003', '38', '神经或精神疾病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2566', 'gb00000003', '41', '癌症', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2567', 'gb00000003', '49', '其他慢性病', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2568', 'gb00000003', '60', '残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2569', 'gb00000003', '61', '视力残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2570', 'gb00000003', '62', '听力残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2571', 'gb00000003', '63', '言语残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2572', 'gb00000003', '64', '肢体残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2573', 'gb00000003', '65', '智力残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2574', 'gb00000003', '66', '精神残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2575', 'gb00000003', '67', '多重残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2576', 'gb00000003', '69', '其他残疾', '', '', '6', '');
INSERT INTO `uc_dict_item` VALUES ('2577', 'gb00000004', '11', '国家公务员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2578', 'gb00000004', '13', '专业技术人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2579', 'gb00000004', '17', '职员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2580', 'gb00000004', '21', '企业管理人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2581', 'gb00000004', '24', '工人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2582', 'gb00000004', '27', '农民', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2583', 'gb00000004', '31', '学生', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2584', 'gb00000004', '37', '现役军人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2585', 'gb00000004', '51', '自由职业者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2586', 'gb00000004', '54', '个体经营者', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2587', 'gb00000004', '70', '无业人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2588', 'gb00000004', '80', '退（离）休人员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2589', 'gb00000004', '90', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2590', 'gb00000005', '10', '人大代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2591', 'gb00000005', '11', '全国人大代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2592', 'gb00000005', '12', '省人大代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2593', 'gb00000005', '13', '地（市）人大代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2594', 'gb00000005', '14', '县（市）人大代表', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2595', 'gb00000005', '20', '政协委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2596', 'gb00000005', '21', '全国政协委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2597', 'gb00000005', '22', '省政协委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2598', 'gb00000005', '23', '地（市）政协委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2599', 'gb00000005', '24', '县（市）政协委员', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2600', 'gb00000006', '1', '两院院士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2601', 'gb00000006', '2', '中国科学院院士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2602', 'gb00000006', '3', '中国工程院院士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2603', 'gb00000007', '10', '研究生教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2604', 'gb00000007', '11', '博士研究生毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2605', 'gb00000007', '12', '博士研究生结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2606', 'gb00000007', '13', '博士研究生肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2607', 'gb00000007', '14', '硕士研究生毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2608', 'gb00000007', '15', '硕士研究生结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2609', 'gb00000007', '16', '硕士研究生肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2610', 'gb00000007', '17', '研究生班毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2611', 'gb00000007', '18', '研究生班结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2612', 'gb00000007', '19', '研究生班肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2613', 'gb00000007', '20', '大学本科教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2614', 'gb00000007', '21', '大学本科毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2615', 'gb00000007', '22', '大学本科结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2616', 'gb00000007', '23', '大学本科肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2617', 'gb00000007', '28', '大学普通班毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2618', 'gb00000007', '30', '大学专科教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2619', 'gb00000007', '31', '大学专科毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2620', 'gb00000007', '32', '大学专科结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2621', 'gb00000007', '33', '大学专科肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2622', 'gb00000007', '40', '中等职业教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2623', 'gb00000007', '41', '中等专科毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2624', 'gb00000007', '42', '中等专科结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2625', 'gb00000007', '43', '中等专科肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2626', 'gb00000007', '44', '职业高中毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2627', 'gb00000007', '45', '职业高中结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2628', 'gb00000007', '46', '职业高中肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2629', 'gb00000007', '47', '技工学校毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2630', 'gb00000007', '48', '技工学校结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2631', 'gb00000007', '49', '技工学校肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2632', 'gb00000007', '60', '普通高级中学教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2633', 'gb00000007', '61', '普通高中毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2634', 'gb00000007', '62', '普通高中结业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2635', 'gb00000007', '63', '普通高中肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2636', 'gb00000007', '70', '初级中学教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2637', 'gb00000007', '71', '初中毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2638', 'gb00000007', '73', '初中肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2639', 'gb00000007', '80', '小学教育', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2640', 'gb00000007', '81', '小学毕业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2641', 'gb00000007', '83', '小学肄业', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2642', 'gb00000007', '90', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2643', 'gb00000008', '01', '汉族', 'HA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2644', 'gb00000008', '02', '蒙古族', 'MG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2645', 'gb00000008', '03', '回族', 'HU', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2646', 'gb00000008', '04', '藏族', 'ZA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2647', 'gb00000008', '05', '维吾尔族', 'UG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2648', 'gb00000008', '06', '苗族', 'MH', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2649', 'gb00000008', '07', '彝族', 'YI', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2650', 'gb00000008', '08', '壮族', 'ZH', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2651', 'gb00000008', '09', '布依族', 'BY', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2652', 'gb00000008', '10', '朝鲜族', 'CS', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2653', 'gb00000008', '11', '满族', 'MA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2654', 'gb00000008', '12', '侗族', 'DO', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2655', 'gb00000008', '13', '瑶族', 'YA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2656', 'gb00000008', '14', '白族', 'BA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2657', 'gb00000008', '15', '土家族', 'TJ', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2658', 'gb00000008', '16', '哈尼族', 'HN', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2659', 'gb00000008', '17', '哈萨克族', 'KZ', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2660', 'gb00000008', '18', '傣族', 'DA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2661', 'gb00000008', '19', '黎族', 'LI', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2662', 'gb00000008', '20', '傈僳族', 'LS', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2663', 'gb00000008', '21', '佤族', 'VA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2664', 'gb00000008', '22', '畲族', 'SH', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2665', 'gb00000008', '23', '高山族', 'GS', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2666', 'gb00000008', '24', '拉祜族', 'LG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2667', 'gb00000008', '25', '水族', 'SU', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2668', 'gb00000008', '26', '东乡族', 'DX', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2669', 'gb00000008', '27', '纳西族', 'NX', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2670', 'gb00000008', '28', '景颇族', 'JP', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2671', 'gb00000008', '29', '柯尔克孜族', 'KG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2672', 'gb00000008', '30', '土族', 'TU', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2673', 'gb00000008', '31', '达斡尔族', 'DU', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2674', 'gb00000008', '32', '仫佬族', 'ML', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2675', 'gb00000008', '33', '羌族', 'QI', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2676', 'gb00000008', '34', '布朗族', 'BL', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2677', 'gb00000008', '35', '撒拉族', 'SL', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2678', 'gb00000008', '36', '毛难族', 'MN', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2679', 'gb00000008', '37', '仡佬族', 'GL', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2680', 'gb00000008', '38', '锡伯族', 'XB', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2681', 'gb00000008', '39', '阿昌族', 'AC', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2682', 'gb00000008', '40', '普米族', 'PM', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2683', 'gb00000008', '41', '塔吉克族', 'TA', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2684', 'gb00000008', '42', '怒族', 'NU', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2685', 'gb00000008', '43', '乌孜别克族', 'UZ', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2686', 'gb00000008', '44', '俄罗斯族', 'RS', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2687', 'gb00000008', '45', '鄂温克族', 'EW', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2688', 'gb00000008', '46', '德昂族', 'DE', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2689', 'gb00000008', '47', '保安族', 'BN', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2690', 'gb00000008', '48', '裕固族', 'YG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2691', 'gb00000008', '49', '京族', 'GI', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2692', 'gb00000008', '50', '塔塔尔族', 'TT', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2693', 'gb00000008', '51', '独龙族', 'DR', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2694', 'gb00000008', '52', '鄂伦春族', 'OR', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2695', 'gb00000008', '53', '赫哲族', 'HZ', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2696', 'gb00000008', '54', '门巴族', 'MB', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2697', 'gb00000008', '55', '珞巴族', 'LB', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2698', 'gb00000008', '56', '基诺族', 'JN', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2699', 'gb00000008', '81', '穿青人族', 'CQ', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2700', 'gb00000008', '97', '其他', 'QT', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2701', 'gb00000008', '98', '外国血统中国籍人士', 'WG', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2702', 'gb00000009', '01', '本人', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2703', 'gb00000009', '02', '户主', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2704', 'gb00000009', '10', '配偶', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2705', 'gb00000009', '11', '夫', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2706', 'gb00000009', '12', '妻', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2707', 'gb00000009', '20', '子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2708', 'gb00000009', '21', '独生子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2709', 'gb00000009', '22', '长子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2710', 'gb00000009', '23', '次子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2711', 'gb00000009', '24', '三子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2712', 'gb00000009', '25', '四子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2713', 'gb00000009', '26', '五子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2714', 'gb00000009', '27', '养子或继子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2715', 'gb00000009', '28', '女婿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2716', 'gb00000009', '29', '其他子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2717', 'gb00000009', '30', '女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2718', 'gb00000009', '31', '独生女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2719', 'gb00000009', '32', '长女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2720', 'gb00000009', '33', '二女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2721', 'gb00000009', '34', '三女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2722', 'gb00000009', '35', '四女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2723', 'gb00000009', '36', '五女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2724', 'gb00000009', '37', '养女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2725', 'gb00000009', '38', '儿媳', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2726', 'gb00000009', '39', '其他女儿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2727', 'gb00000009', '40', '孙子、孙女或外孙子、外孙女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2728', 'gb00000009', '41', '孙子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2729', 'gb00000009', '42', '孙女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2730', 'gb00000009', '43', '外孙子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2731', 'gb00000009', '44', '外孙女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2732', 'gb00000009', '45', '孙媳妇或外孙媳妇', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2733', 'gb00000009', '46', '孙女婿或外孙女婿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2734', 'gb00000009', '47', '曾孙子或外曾孙子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2735', 'gb00000009', '48', '曾孙女或外曾孙女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2736', 'gb00000009', '49', '其他孙子、孙女或外孙子、外孙女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2737', 'gb00000009', '50', '父母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2738', 'gb00000009', '51', '父亲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2739', 'gb00000009', '52', '母亲', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2740', 'gb00000009', '53', '公公', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2741', 'gb00000009', '54', '婆婆', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2742', 'gb00000009', '55', '岳父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2743', 'gb00000009', '56', '岳母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2744', 'gb00000009', '57', '继父或养父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2745', 'gb00000009', '58', '继母或养母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2746', 'gb00000009', '59', '其他父母关系', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2747', 'gb00000009', '60', '祖父母或外祖父母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2748', 'gb00000009', '61', '祖父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2749', 'gb00000009', '62', '祖母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2750', 'gb00000009', '63', '外祖父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2751', 'gb00000009', '64', '外祖母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2752', 'gb00000009', '65', '配偶的祖父母或外祖父母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2753', 'gb00000009', '66', '曾祖父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2754', 'gb00000009', '67', '曾祖母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2755', 'gb00000009', '68', '配偶的曾祖父母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2756', 'gb00000009', '69', '其他祖父母或外祖父母关系', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2757', 'gb00000009', '70', '兄弟姐妹', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2758', 'gb00000009', '71', '兄', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2759', 'gb00000009', '72', '嫂', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2760', 'gb00000009', '73', '弟', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2761', 'gb00000009', '74', '弟媳', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2762', 'gb00000009', '75', '姐姐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2763', 'gb00000009', '76', '姐夫', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2764', 'gb00000009', '77', '妹妹', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2765', 'gb00000009', '78', '妹夫', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2766', 'gb00000009', '79', '其他兄弟姐妹', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2767', 'gb00000009', '80/99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2768', 'gb00000009', '81', '伯父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2769', 'gb00000009', '82', '伯母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2770', 'gb00000009', '83', '叔父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2771', 'gb00000009', '84', '婶母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2772', 'gb00000009', '85', '舅父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2773', 'gb00000009', '86', '舅母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2774', 'gb00000009', '87', '姨父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2775', 'gb00000009', '88', '姨母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2776', 'gb00000009', '89', '姑父', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2777', 'gb00000009', '90', '姑母', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2778', 'gb00000009', '91', '堂兄弟、堂姐妹', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2779', 'gb00000009', '92', '表兄弟、表姐妹', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2780', 'gb00000009', '93', '侄子', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2781', 'gb00000009', '94', '侄女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2782', 'gb00000009', '95', '外甥', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2783', 'gb00000009', '96', '外甥女', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2784', 'gb00000009', '97', '其他亲属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2785', 'gb00000009', '99', '非亲属', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2786', 'gb00000010', '01', '中共党员', '', '', '0', '中国共产党党员');
INSERT INTO `uc_dict_item` VALUES ('2787', 'gb00000010', '02', '中共预备党员', '', '', '0', '中国共产党预备党员');
INSERT INTO `uc_dict_item` VALUES ('2788', 'gb00000010', '03', '共青团员', '', '', '0', '中国共产主义青年团团员');
INSERT INTO `uc_dict_item` VALUES ('2789', 'gb00000010', '04', '民革会员', '', '', '0', '中国国民党革命委员会会员');
INSERT INTO `uc_dict_item` VALUES ('2790', 'gb00000010', '05', '民盟盟员', '', '', '0', '中国民主同盟盟员');
INSERT INTO `uc_dict_item` VALUES ('2791', 'gb00000010', '06', '民建会员', '', '', '0', '中国民主建国会会员');
INSERT INTO `uc_dict_item` VALUES ('2792', 'gb00000010', '07', '民进会员', '', '', '0', '中国民主促进会会员');
INSERT INTO `uc_dict_item` VALUES ('2793', 'gb00000010', '08', '农工党党员', '', '', '0', '中国农工民主党党员');
INSERT INTO `uc_dict_item` VALUES ('2794', 'gb00000010', '09', '致公党党员', '', '', '0', '中国致公党党员');
INSERT INTO `uc_dict_item` VALUES ('2795', 'gb00000010', '10', '九三学社社员', '', '', '0', '九三学社社员');
INSERT INTO `uc_dict_item` VALUES ('2796', 'gb00000010', '11', '台盟盟员', '', '', '0', '台湾民主自治同盟盟员');
INSERT INTO `uc_dict_item` VALUES ('2797', 'gb00000010', '12', '无党派民主人士', '', '', '0', '无党派民主人士');
INSERT INTO `uc_dict_item` VALUES ('2798', 'gb00000010', '13', '群众', '', '', '0', '群众');
INSERT INTO `uc_dict_item` VALUES ('2799', 'gb00000011', '01', '中国共产党', '', '', '0', '中共');
INSERT INTO `uc_dict_item` VALUES ('2800', 'gb00000011', '04', '中国国民党革命委员会', '', '', '0', '民革');
INSERT INTO `uc_dict_item` VALUES ('2801', 'gb00000011', '05', '中国民主同盟', '', '', '0', '民盟');
INSERT INTO `uc_dict_item` VALUES ('2802', 'gb00000011', '06', '中国民主建国会', '', '', '0', '民建');
INSERT INTO `uc_dict_item` VALUES ('2803', 'gb00000011', '07', '中国民主促进会', '', '', '0', '民进');
INSERT INTO `uc_dict_item` VALUES ('2804', 'gb00000011', '08', '中国农工民主党', '', '', '0', '农工党');
INSERT INTO `uc_dict_item` VALUES ('2805', 'gb00000011', '09', '中国致公党', '', '', '0', '致公党');
INSERT INTO `uc_dict_item` VALUES ('2806', 'gb00000011', '10', '九三学社', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2807', 'gb00000011', '11', '台湾民主自治同盟', '', '', '0', '台盟');
INSERT INTO `uc_dict_item` VALUES ('2808', 'gb00000011', '12', '无党派', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2809', 'gb00000012', '1', '名誉博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2810', 'gb00000012', '2', '博士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2811', 'gb00000012', '201', '哲学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2812', 'gb00000012', '202', '经济学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2813', 'gb00000012', '203', '法学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2814', 'gb00000012', '204', '教育学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2815', 'gb00000012', '205', '文学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2816', 'gb00000012', '206', '历史学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2817', 'gb00000012', '207', '理学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2818', 'gb00000012', '208', '工学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2819', 'gb00000012', '209', '农学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2820', 'gb00000012', '210', '医学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2821', 'gb00000012', '211', '军事学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2822', 'gb00000012', '212', '管理学博士学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2823', 'gb00000012', '245', '临床医学博士专业学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2824', 'gb00000012', '248', '兽医博士专业学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2825', 'gb00000012', '250', '口腔医学博士专业学位', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2826', 'gb00000012', '3', '硕士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2827', 'gb00000012', '301', '哲学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2828', 'gb00000012', '302', '经济学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2829', 'gb00000012', '303', '法学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2830', 'gb00000012', '304', '教育学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2831', 'gb00000012', '305', '文学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2832', 'gb00000012', '306', '历史学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2833', 'gb00000012', '307', '理学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2834', 'gb00000012', '308', '工学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2835', 'gb00000012', '309', '农学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2836', 'gb00000012', '310', '医学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2837', 'gb00000012', '311', '军事学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2838', 'gb00000012', '312', '管理学硕士学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2839', 'gb00000012', '341', '法律硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2840', 'gb00000012', '342', '教育硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2841', 'gb00000012', '343', '工程硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2842', 'gb00000012', '344', '建筑学硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2843', 'gb00000012', '345', '临床学硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2844', 'gb00000012', '346', '工商管理硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2845', 'gb00000012', '347', '农业推广硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2846', 'gb00000012', '348', '兽医硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2847', 'gb00000012', '349', '公共管理硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2848', 'gb00000012', '350', '口腔医学硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2849', 'gb00000012', '351', '公共卫生硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2850', 'gb00000012', '352', '军事硕士专业学位', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2851', 'gb00000012', '4', '学士', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2852', 'gb00000012', '401', '哲学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2853', 'gb00000012', '402', '经济学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2854', 'gb00000012', '403', '法学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2855', 'gb00000012', '404', '教育学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2856', 'gb00000012', '405', '文学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2857', 'gb00000012', '406', '历史学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2858', 'gb00000012', '407', '理学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2859', 'gb00000012', '408', '工学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2860', 'gb00000012', '409', '农学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2861', 'gb00000012', '410', '医学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2862', 'gb00000012', '411', '军事学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2863', 'gb00000012', '412', '管理学学士学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2864', 'gb00000012', '444', '建筑学学士专业学位', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2865', 'gb00000013', '1', '精通', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2866', 'gb00000013', '2', '熟练', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2867', 'gb00000013', '3', '良好', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2868', 'gb00000013', '4', '一般', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2869', 'gb00000014', '1', '大学外语专业考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2870', 'gb00000014', '11', '大学外语专业考试笔试', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2871', 'gb00000014', '111', '大学外语专业笔试八级', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('2872', 'gb00000014', '112', '大学外语专业笔试四级', '', '', '11', '');
INSERT INTO `uc_dict_item` VALUES ('2873', 'gb00000014', '12', '大学外语专业考试口试', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2874', 'gb00000014', '121', '大学外语专业口试八级', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('2875', 'gb00000014', '122', '大学外语专业口试四级', '', '', '12', '');
INSERT INTO `uc_dict_item` VALUES ('2876', 'gb00000014', '2', '大学非外语专业考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2877', 'gb00000014', '21', '大学非外语专业考试笔试', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2878', 'gb00000014', '211', '大学六级', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('2879', 'gb00000014', '212', '大学四级', '', '', '21', '');
INSERT INTO `uc_dict_item` VALUES ('2880', 'gb00000014', '22', '大学非外语专业考试口试', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2881', 'gb00000014', '221', '大学外语四、六级考试口语考试 A 等', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('2882', 'gb00000014', '222', '大学外语四、六级考试口语考试 B 等', '', '', '22', '');
INSERT INTO `uc_dict_item` VALUES ('2883', 'gb00000014', '3', '全国外语等级考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2884', 'gb00000014', '301', '全国外语等级考试五级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2885', 'gb00000014', '302', '全国外语等级考试四级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2886', 'gb00000014', '303', '全国外语等级考试三级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2887', 'gb00000014', '304', '全国外语等级考试二级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2888', 'gb00000014', '305', '全国外语等级考试一级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2889', 'gb00000014', '306', '全国外语等级考试一级（B）', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2890', 'gb00000014', '4', '全国职称外语考试', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2891', 'gb00000014', '401', '全国职称外语考试 A 级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2892', 'gb00000014', '402', '全国职称外语考试 B 级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2893', 'gb00000014', '403', '全国职称外语考试 C 级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2894', 'gb00000015', '1', '国家公务员纪律处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2895', 'gb00000015', '10', '警告', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2896', 'gb00000015', '12', '记过', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2897', 'gb00000015', '13', '记大过', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2898', 'gb00000015', '14', '降级', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2899', 'gb00000015', '17', '撤职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2900', 'gb00000015', '19', '开除', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2901', 'gb00000015', '2', '企业职工纪律处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2902', 'gb00000015', '20', '警告', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2903', 'gb00000015', '22', '记过', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2904', 'gb00000015', '23', '记大过', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2905', 'gb00000015', '24', '降级', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2906', 'gb00000015', '27', '撤职', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2907', 'gb00000015', '28', '留用察看', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2908', 'gb00000015', '29', '开除', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2909', 'gb00000015', '3', '中国人民解放军军人纪律处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2910', 'gb00000015', '30', '警告', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2911', 'gb00000015', '31', '严重警告', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2912', 'gb00000015', '32', '记过', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2913', 'gb00000015', '33', '记大过', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2914', 'gb00000015', '34', '降一级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2915', 'gb00000015', '35', '降二级', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2916', 'gb00000015', '36', '降职', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2917', 'gb00000015', '37', '撤职', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2918', 'gb00000015', '39', '开除军籍', '', '', '3', '');
INSERT INTO `uc_dict_item` VALUES ('2919', 'gb00000015', '4', '中国共产主义青年团团员纪律处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2920', 'gb00000015', '40', '警告', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2921', 'gb00000015', '41', '严重警告', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2922', 'gb00000015', '47', '撤销团内职务', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2923', 'gb00000015', '48', '留团察看', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2924', 'gb00000015', '49', '开除团籍', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2925', 'gb00000015', '5', '中国共产党党员纪律处分', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2926', 'gb00000015', '50', '警告', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('2927', 'gb00000015', '51', '严重警告', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('2928', 'gb00000015', '57', '撤销党内职务', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('2929', 'gb00000015', '58', '留党察看', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('2930', 'gb00000015', '59', '开除党籍', '', '', '5', '');
INSERT INTO `uc_dict_item` VALUES ('2931', 'gb00000016', '100', '内资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2932', 'gb00000016', '110', '国有全资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2933', 'gb00000016', '120', '集体全资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2934', 'gb00000016', '130', '股份合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2935', 'gb00000016', '140', '联营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2936', 'gb00000016', '141', '国有联营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2937', 'gb00000016', '142', '集体联营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2938', 'gb00000016', '143', '国有与集体联营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2939', 'gb00000016', '149', '其他联营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2940', 'gb00000016', '150', '有限责任(公司)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2941', 'gb00000016', '151', '国有独资（公司）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2942', 'gb00000016', '159', '其他有限责任(公司)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2943', 'gb00000016', '160', '股份有限(公司)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2944', 'gb00000016', '170', '私有', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2945', 'gb00000016', '171', '私有独资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2946', 'gb00000016', '172', '私有合伙', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2947', 'gb00000016', '173', '私营有限责任（公司)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2948', 'gb00000016', '174', '私营股份有限（公司）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2949', 'gb00000016', '175', '个体经营', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2950', 'gb00000016', '179', '其他私有', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2951', 'gb00000016', '190', '其他内资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2952', 'gb00000016', '200', '港、澳、台投资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2953', 'gb00000016', '210', '内地和港、澳、台合资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2954', 'gb00000016', '220', '内地和港、澳、台合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2955', 'gb00000016', '230', '港、澳、台独资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2956', 'gb00000016', '240', '港、澳、台投资股份有限（公司）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2957', 'gb00000016', '290', '其他港、澳、台投资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2958', 'gb00000016', '300', '国外投资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2959', 'gb00000016', '310', '中外合资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2960', 'gb00000016', '320', '中外合作', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2961', 'gb00000016', '330', '外资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2962', 'gb00000016', '340', '国外投资股份有限（公司）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2963', 'gb00000016', '390', '其他国外投资', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2964', 'gb00000016', '900', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2965', 'gb00000017', '1', '公务员职务级别', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2966', 'gb00000017', '101', '国家级正职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2967', 'gb00000017', '102', '国家级副职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2968', 'gb00000017', '111', '省部级正职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2969', 'gb00000017', '112', '省部级副职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2970', 'gb00000017', '121', '厅局级正职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2971', 'gb00000017', '122', '厅局级副职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2972', 'gb00000017', '131', '县处级正职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2973', 'gb00000017', '132', '县处级副职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2974', 'gb00000017', '141', '乡科级正职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2975', 'gb00000017', '142', '乡科级副职', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2976', 'gb00000017', '150', '科员级', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2977', 'gb00000017', '160', '办事员级', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2978', 'gb00000017', '199', '未定职公务员', '', '', '1', '');
INSERT INTO `uc_dict_item` VALUES ('2979', 'gb00000017', '2', '职员级别', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2980', 'gb00000017', '211', '一级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2981', 'gb00000017', '212', '二级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2982', 'gb00000017', '221', '三级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2983', 'gb00000017', '222', '四级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2984', 'gb00000017', '231', '五级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2985', 'gb00000017', '232', '六级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2986', 'gb00000017', '241', '七级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2987', 'gb00000017', '242', '八级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2988', 'gb00000017', '250', '九级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2989', 'gb00000017', '260', '十级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2990', 'gb00000017', '299', '未定级职员', '', '', '2', '');
INSERT INTO `uc_dict_item` VALUES ('2991', 'gb00000017', '4', '专业技术职务级别', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('2992', 'gb00000017', '410', '高级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2993', 'gb00000017', '411', '正高级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2994', 'gb00000017', '412', '副高级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2995', 'gb00000017', '420', '中级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2996', 'gb00000017', '430', '初级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2997', 'gb00000017', '434', '助理级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2998', 'gb00000017', '435', '员级', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('2999', 'gb00000017', '499', '未定职级专业技术人员', '', '', '4', '');
INSERT INTO `uc_dict_item` VALUES ('3000', 'gb00000018', '110', '数学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3001', 'gb00000018', '120', '信息科学与系统科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3002', 'gb00000018', '130', '力学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3003', 'gb00000018', '140', '物理学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3004', 'gb00000018', '150', '化学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3005', 'gb00000018', '160', '天文学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3006', 'gb00000018', '170', '地球科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3007', 'gb00000018', '180', '生物学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3008', 'gb00000018', '190', '心理学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3009', 'gb00000018', '210', '农学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3010', 'gb00000018', '220', '林学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3011', 'gb00000018', '230', '畜牧、兽医科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3012', 'gb00000018', '240', '水产学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3013', 'gb00000018', '310', '基础医学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3014', 'gb00000018', '320', '临床医学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3015', 'gb00000018', '330', '预防医学与公共卫生学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3016', 'gb00000018', '340', '军事医学与特种医学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3017', 'gb00000018', '350', '药学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3018', 'gb00000018', '360', '中医学与中药学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3019', 'gb00000018', '410', '工程与技术科学基础学科', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3020', 'gb00000018', '413', '信息与系统科学相关工程与技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3021', 'gb00000018', '416', '自然科学相关工程与技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3022', 'gb00000018', '420', '测绘科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3023', 'gb00000018', '430', '材料科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3024', 'gb00000018', '440', '矿山工程技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3025', 'gb00000018', '450', '冶金工程技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3026', 'gb00000018', '460', '机械工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3027', 'gb00000018', '470', '动力与电气工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3028', 'gb00000018', '480', '能源科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3029', 'gb00000018', '490', '核工程技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3030', 'gb00000018', '510', '电子与通信技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3031', 'gb00000018', '520', '计算机科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3032', 'gb00000018', '530', '化学工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3033', 'gb00000018', '535', '产品应用相关工程与技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3034', 'gb00000018', '540', '纺织科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3035', 'gb00000018', '550', '食品科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3036', 'gb00000018', '560', '土木建筑工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3037', 'gb00000018', '570', '水利工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3038', 'gb00000018', '580', '交通运输工程', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3039', 'gb00000018', '590', '航空、航天科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3040', 'gb00000018', '610', '环境科学技术及资源科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3041', 'gb00000018', '620', '安全科学技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3042', 'gb00000018', '630', '管理学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3043', 'gb00000018', '710', '马克思主义', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3044', 'gb00000018', '720', '哲学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3045', 'gb00000018', '730', '宗教学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3046', 'gb00000018', '740', '语言学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3047', 'gb00000018', '750', '文学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3048', 'gb00000018', '760', '艺术学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3049', 'gb00000018', '770', '历史学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3050', 'gb00000018', '780', '考古学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3051', 'gb00000018', '790', '经济学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3052', 'gb00000018', '810', '政治学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3053', 'gb00000018', '820', '法学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3054', 'gb00000018', '830', '军事学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3055', 'gb00000018', '840', '社会学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3056', 'gb00000018', '850', '民族学与文化学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3057', 'gb00000018', '860', '新闻学与传播学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3058', 'gb00000018', '870', '图书馆、情报与文献学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3059', 'gb00000018', '880', '教育学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3060', 'gb00000018', '890', '体育科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3061', 'gb00000018', '910', '统计学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3062', 'gb00000019', '10', '录用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3063', 'gb00000019', '20', '聘用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3064', 'gb00000019', '21', '订立短期合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3065', 'gb00000019', '22', '订立中期合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3066', 'gb00000019', '23', '订立长期合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3067', 'gb00000019', '24', '订立项目合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3068', 'gb00000019', '30', '聘任', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3069', 'gb00000019', '40', '招用', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3070', 'gb00000019', '41', '订立固定期限劳动合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3071', 'gb00000019', '42', '订立无固定期限劳动合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3072', 'gb00000019', '43', '订立以完成一定工作任务为期限的劳动合同', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3073', 'gb00000019', '50', '特定情形', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3074', 'gb00000019', '51', '劳务派遣', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3075', 'gb00000019', '52', '非全日制用工', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3076', 'gb00000019', '90', '其他情形', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3077', 'gb00000020', '11', '外交护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3078', 'gb00000020', '12', '公务护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3079', 'gb00000020', '13', '因公普通护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3080', 'gb00000020', '14', '普通护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3081', 'gb00000020', '15', '中华人民共和国旅行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3082', 'gb00000020', '16', '台湾居民来往大陆通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3083', 'gb00000020', '17', '海员证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3084', 'gb00000020', '18', '机组人员证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3085', 'gb00000020', '19', '铁路员工证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3086', 'gb00000020', '20', '中华人民共和国人出境通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3087', 'gb00000020', '21', '往来港澳通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3088', 'gb00000020', '23', '前往港澳通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3089', 'gb00000020', '24', '港澳同胞回乡证(港澳居民来往内地通行证)', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3090', 'gb00000020', '25', '大陆居民往来台湾通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3091', 'gb00000020', '27', '往来香港澳门特别行政区通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3092', 'gb00000020', '28', '华侨回国定居证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3093', 'gb00000020', '29', '台湾同胞定居证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3094', 'gb00000020', '30', '外国人出人境证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3095', 'gb00000020', '31', '外国人旅行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3096', 'gb00000020', '32', '外国人居留证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3097', 'gb00000020', '33', '外国人临时居留证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3098', 'gb00000020', '35', '人籍证书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3099', 'gb00000020', '36', '出籍证书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3100', 'gb00000020', '37', '复籍证书', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3101', 'gb00000020', '38', '暂住证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3102', 'gb00000020', '40', '出海渔船民证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3103', 'gb00000020', '41', '临时出海渔船民证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3104', 'gb00000020', '42', '出海船舶户口簿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3105', 'gb00000020', '43', '出海船舶户口证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3106', 'gb00000020', '44', '粤港澳流动渔民证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3107', 'gb00000020', '45', '粤港澳临时流动渔民证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3108', 'gb00000020', '46', '粤港澳流动渔船户口簿', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3109', 'gb00000020', '47', '搭靠台轮许可证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3110', 'gb00000020', '48', '劳务人员登轮作业证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3111', 'gb00000020', '49', '台湾居民登陆证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3112', 'gb00000020', '50', '贸易证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3113', 'gb00000020', '60', '边境通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3114', 'gb00000020', '61', '深圳市过境耕作证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3115', 'gb00000020', '70', '香港特别行政区护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3116', 'gb00000020', '71', '澳门特别行政区护照', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3117', 'gb00000020', '81', '缅甸中方(缅方)通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3118', 'gb00000020', '82', '云南边境地区境外边民人出境证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3119', 'gb00000020', '90', '中朝边境地区出人境通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3120', 'gb00000020', '91', '朝中边境地区居民过境通行证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3121', 'gb00000020', '92', '鸭绿江、图们江水文作业证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3122', 'gb00000020', '93', '中朝流筏固定代表证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3123', 'gb00000020', '94', '中朝(朝中)鸭绿江、图们江航行船舶船员证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3124', 'gb00000020', '95', '中朝(朝中)边境地区公安总代表证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3125', 'gb00000020', '96', '中朝(朝中)边境地区公安副总代表证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3126', 'gb00000020', '97', '中朝(朝中)边境地区公安代表证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3127', 'gb00000020', '99', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3128', 'gb00000021', '0', '未落常住户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3129', 'gb00000021', '1', '非农业家庭户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3130', 'gb00000021', '2', '农业家庭户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3131', 'gb00000021', '3', '非农业集体户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3132', 'gb00000021', '4', '农业集体户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3133', 'gb00000021', '5', '自理口粮户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3134', 'gb00000021', '6', '寄住户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3135', 'gb00000021', '7', '暂住户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3136', 'gb00000021', '8', '其他户口', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3137', 'gb00000022', 'C', '乘务签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3138', 'gb00000022', 'D', '定居签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3139', 'gb00000022', 'E', '特区旅游签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3140', 'gb00000022', 'F', '访问签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3141', 'gb00000022', 'G', '过境签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3142', 'gb00000022', 'I', '外国人永久居留证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3143', 'gb00000022', 'J', '常住记者签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3144', 'gb00000022', 'L', '旅游签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3145', 'gb00000022', 'M', '免办签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3146', 'gb00000022', 'P', '临时来华记者证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3147', 'gb00000022', 'R', '居留许可', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3148', 'gb00000022', 'S', 'APEC 商务旅行卡', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3149', 'gb00000022', 'T', '团体签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3150', 'gb00000022', 'U', '公务签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3151', 'gb00000022', 'W', '外交签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3152', 'gb00000022', 'X', '学习签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3153', 'gb00000022', 'Y', '礼遇签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3154', 'gb00000022', 'Z', '职业签证', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3155', 'gb00000022', 'Q', '其他', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3156', 'gb00000023', '11', '赴港澳探亲签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3157', 'gb00000023', '12', '赴港澳旅游签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3158', 'gb00000023', '13', '赴港澳商务签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3159', 'gb00000023', '14', '赴港澳培训签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3160', 'gb00000023', '15', '赴港澳乘务签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3161', 'gb00000023', '16', '赴港澳就业签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3162', 'gb00000023', '17', '赴港澳就业家属签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3163', 'gb00000023', '18', '赴澳门学习签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3164', 'gb00000023', '19', '赴港澳其他签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3165', 'gb00000023', '1A', '广东船员乘务签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3166', 'gb00000023', '1B', '赴港澳访问签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3167', 'gb00000023', '1C', '赴香港学习签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3168', 'gb00000023', '1D', '赴香港学习者家属签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3169', 'gb00000023', '1E', '人才计划就业者家属签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3170', 'gb00000023', '1F', '赴港澳培训者家属签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3171', 'gb00000023', '21', '赴台探亲签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3172', 'gb00000023', '22', '赴台应邀签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3173', 'gb00000023', '23', '赴台定居签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3174', 'gb00000023', '24', '赴台居留签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3175', 'gb00000023', '25', '赴台旅游签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3176', 'gb00000023', '26', '赴台乘务签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3177', 'gb00000023', '27', '赴台商务签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3178', 'gb00000023', '29', '赴台其他签注', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3179', 'zxx0000001', '13', '语文', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3180', 'zxx0000001', '14', '数学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3181', 'zxx0000001', '15', '科学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3182', 'zxx0000001', '22', '体育与健康', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3183', 'zxx0000001', '23', '艺术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3184', 'zxx0000001', '24', '音乐', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3185', 'zxx0000001', '25', '美术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3186', 'zxx0000001', '40', '外语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3187', 'zxx0000001', '41', '英语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3188', 'zxx0000001', '42', '日语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3189', 'zxx0000001', '43', '俄语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3190', 'zxx0000001', '49', '其他外国语', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3191', 'zxx0000001', '60', '综合实践活动', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3192', 'zxx0000001', '61', '信息技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3193', 'zxx0000001', '62', '劳动与技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3194', 'zxx0000001', '12', '思想品德（政治）', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3195', 'zxx0000001', '16', '物理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3196', 'zxx0000001', '17', '化学', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3197', 'zxx0000001', '18', '生物', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3198', 'zxx0000001', '19', '历史与社会', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3199', 'zxx0000001', '20', '地理', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3200', 'zxx0000001', '21', '历史', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3201', 'zxx0000001', '26', '信息技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3202', 'zxx0000001', '27', '通用技术', '', '', '0', '');
INSERT INTO `uc_dict_item` VALUES ('3203', 'jg00000246', '010', '高等学校老师', '', '0', '0', null);
INSERT INTO `uc_dict_item` VALUES ('3204', 'jg00000246', '011', '教授', '', '010', '0', null);
INSERT INTO `uc_dict_item` VALUES ('3205', 'jg00000246', '012', '副教授', '', '010', '0', null);
INSERT INTO `uc_dict_item` VALUES ('3206', 'jg00000246', '013', '讲师', '', '010', '0', null);
INSERT INTO `uc_dict_item` VALUES ('3207', 'jg00000246', '014', '助教', '', '010', '0', null);

-- ----------------------------
-- Table structure for uc_dict_type
-- ----------------------------
DROP TABLE IF EXISTS `uc_dict_type`;
CREATE TABLE `uc_dict_type` (
  `dataid` char(10) NOT NULL,
  `datacn` varchar(50) NOT NULL,
  `dataen` varchar(20) NOT NULL,
  `datacategeory` int(11) NOT NULL DEFAULT '0',
  `datastandard` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`dataid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_dict_type
-- ----------------------------
INSERT INTO `uc_dict_type` VALUES ('gb00000001', '性别', 'XB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000002', '婚姻状况', 'HYZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000003', '健康状况', 'JKZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000004', '个人身份', 'GRSF', '0', '0', '从业状况');
INSERT INTO `uc_dict_type` VALUES ('gb00000005', '人大代表或政协委员', 'RDDBHZXWY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000006', '院士代码', 'YS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000007', '学历', 'XL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000008', '民族', 'MZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000009', '家庭关系', 'JTGX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000010', '政治面貌', 'ZZMM', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000011', '党派', 'DP', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000012', '中华人民共和国学位', 'ZHRMGHGXW', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000013', '语种熟练程度', 'YZSLCD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000014', '外语考试等级 ', 'WYKSDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000015', '纪律处分 ', 'JLCF', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000016', '经济类型分类', 'JJLXFL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000017', '职务级别', 'ZWJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000018', '一级学科分类', 'YJXKFL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000019', '用人单位用人形式分类', 'YRDWYRXSFL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000020', '护照证件种类', 'HZZJZL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000021', '户口类别', 'HKLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000022', '中国签证种类', 'ZGQZZL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('gb00000023', '签注种类', 'QZZL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000001', '办学类型', 'BXLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000002', '单位办别', 'DWBB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000003', '单位类别', 'DWLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000004', '所在地城乡类型', 'SZDCXLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000005', '所在地区经济属性', 'SZDQJJSX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000006', '学校办别', 'XXBB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000007', '学校变更', 'XXBG', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000008', '学校单位层次', 'XXDWCC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000009', '学校（教育机构）举办者', 'XXJYJGJBZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000010', '学校性质', 'XXXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000011', '安全教学形式', 'AQJXXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000012', '毕业去向', 'BYQX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000013', '处分名称', 'CFMC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000014', '残疾人类型', 'CJRLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000015', '附加分类别', 'FJFLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000016', '分流方向', 'FLFX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000017', '攻读类型', 'GDLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000018', '高考科目', 'GKKM', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000019', '高校毕业去向', 'GXBYQX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000020', '工作岗位性质', 'GZGWXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000021', '获得学历方式', 'HDXLFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000022', '获奖类型', 'HJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000023', '户口迁出状况', 'HKQCZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000024', '就读方式', 'JDFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000025', '奖励方式', 'JLFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000026', '奖励资助资金来源', 'JLZZZJLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000027', '缴纳学费状况', 'JNXFZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000028', '竞赛级别', 'JSJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000029', '家庭类别', 'JTLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000030', '奖学金类型', 'JXJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000031', '就业落实方式', 'JYLSFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000032', '困难程度', 'KNCD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000033', '困难原因', 'KNYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000034', '来华留学生经费来源', 'LHLXSJFLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000035', '来华留学生类别', 'LHLXSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000036', '来华留学生收费类别', 'LHLXSSFLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000037', '来华留学生重点关注类别', 'LHLXSZDGZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000038', '录取类别', 'LQLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000039', '培养层次', 'PYCC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000040', '培养方式', 'PYFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000041', '勤工类别', 'QGLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000042', '入学方式', 'RXFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000043', '师范学生类别', 'SFXSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000044', '社会实践等级', 'SHSJDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000045', '社会实践类型', 'SHSJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000046', '三助类别', 'SZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000047', '体检项目类别', 'TJXMLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000048', '违纪类别', 'WJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000049', '学籍异动类别', 'XJYDLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000050', '学籍异动原因', 'XJYDYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000051', '学生变动', 'XSBD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000052', '学生当前状态', 'XSDQZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000053', '学生获奖类别', 'XSHJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000054', '学生类别', 'XSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000055', '学生来源', 'XSLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000056', '学生年龄', 'XSNL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000057', '学生收费调整方式', 'XSSFTZFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000058', '学生体质达标', 'XSTZDB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000059', '休退学原因', 'XTXYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000060', '研究生录取调剂类别', 'YJSLQTJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000061', '研究生专项计划类别', 'YJSZXJHLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000062', '严重不良行为', 'YZBLXW', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000063', '研究生入学方式', 'YJSRXFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000064', '招聘会类别', 'ZPHLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000065', '中小学学生来源', 'ZXXXSLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000066', '班额', 'BE', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000067', '监考人类别', 'JKRLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000068', '教室类型', 'JSLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000069', '教室占用情况', 'JSZYQK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000070', '教学类型', 'JXLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000071', '教学用房性质', 'JXYFXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000072', '课程级别', 'KCJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000073', '课程类别', 'KCLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000074', '课程类型', 'KCLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000075', '课程属性', 'KCSX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000076', '课程性质', 'KCXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000077', '考试方式', 'KSFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000078', '考试形式', 'KSXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000079', '考试性质', 'KSXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000080', '年级', 'NJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000081', '培训进修时限', 'PXJXSX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000082', '培训证书类型', 'PXZSLXY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000083', '培训专业所属产业', 'PXZYSSCY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000084', '入学年龄', 'RXNL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000085', '缺考舞弊', 'QKWB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000086', '授课方式', 'SKFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000087', '少数民族双语教学模式', 'SSMZSYJXMS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000088', '实验类别', 'SYLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000089', '授予权类别', 'SYQLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000090', '实验要求', 'SYYQ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000091', '实验者类别', 'SYZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000092', '特殊教育学习阶段', 'TSJYXXJD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000093', '学期', 'XQ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000094', '学位类别', 'XWLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000095', '校学位委员会结论', 'XXWWYHJL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000096', '学制', 'XZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000097', '幼儿班级类型', 'YEBJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000098', '注册状况', 'ZCZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000099', '招生对象', 'ZSDX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000100', '中小学班级类型', 'ZXXBJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000101', '中小学编制类别', 'ZXXBZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000102', '中小学课程', 'ZXXKC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000103', '中小学课程等级', 'ZXXKCDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000104', '中小学实验类别', 'ZXXSYLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000105', '中小学实验室类别', 'ZXXSYSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000106', '编制类别', 'BZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000107', '编制异动', 'BZYD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000108', '辞去社会兼职或学术团体职务原因', 'CQSHJZHXSTTZWYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000109', '导师类别', 'DSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000110', '辅导员年龄', 'FDYNL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000111', '岗位类别', 'GWLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000112', '岗位职业', 'GWZY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000113', '高校教职工来源', 'GXJZGLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000114', '工作年限', 'GZNX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000115', '行业工种类别', 'HYGZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000116', '教师变动', 'JSBD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000117', '教师获奖类别', 'JSHJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000118', '教师流动类别', 'JSLDLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000119', '教师年龄', 'JSNL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000120', '教职工当前状态', 'JZGDQZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000121', '教职工类别', 'JZGLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000122', '教职工来源', 'JZGLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000123', '离岗原因', 'LGYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000124', '离校离职原因', 'LXLZYY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000125', '聘任情况', 'PRQK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000126', '聘用性质', 'PYXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000127', '任课角色', 'RKJS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000128', '任课课程类别', 'RKKCLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000129', '任课学段', 'RKXD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000130', '任课状况', 'RKZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000131', '外籍专家来华渠道', 'WJZJLHQD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000132', '外籍专家证类型', 'WJZJZLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000133', '委员会任职', 'WYHRZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000134', '专家类别', 'ZJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000135', '职务类别', 'ZWLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000136', '出国目的', 'CGMD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000137', '港澳台侨外', 'GATQW', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000138', '国（境）外协作单位类型', 'GJWXZDWLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000139', '汉语水平考试成绩', 'HSKCJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000140', '级别', 'JB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000141', '奖励等级', 'JLDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000142', '来访代表团成员身份', 'LFDBTCYSF', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000143', '来访访问类别', 'LFFWLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000144', '普通话水平等级', 'PTHSPDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000145', '是否标志', 'SFBZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000146', '身份证件类型', 'SFZJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000147', '社会单位性质', 'SHDWXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000148', '世界各洲名称', 'SJGZMC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000149', '血型', 'XX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000150', '出版社级别', 'CBSJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000151', '成果获奖类别', 'CGHJLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000152', '成果类型', 'CGLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000153', '活动类型', 'HDLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000154', '会议举办形式', 'HYJBXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000155', '合作形式', 'HZXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000156', '鉴定结论', 'JDJL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000157', '机构职能类型', 'JGZNLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000158', '计划完成情况', 'JHWCQK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000159', '角色', 'JS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000160', '刊物级别', 'KWJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000161', '论文报告形式', 'LWBGXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000162', '论著类别', 'LZLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000163', '社会经济效益', 'SHJJXY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000164', '受让方类型', 'SRFLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000165', '完成形式', 'WCXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000166', '学科门类（科技）', 'XKMLKJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000167', '项目经费来源', 'XMJFLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000168', '项目类型', 'XMLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000169', '项目来源', 'XMLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000170', '项目执行状态', 'XMZXZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000171', '学术会议等级', 'XSHYDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000172', '学术交流类型', 'XSJLLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000173', '学术团体级别', 'XSTTJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000174', '协作单位类型', 'XZDWLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000175', '专利法律状态', 'ZLFLZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000176', '专利类型', 'ZLLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000177', '专利批准形式', 'ZLPZXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000178', '产权', 'CQ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000179', '厕所情况', 'CSQK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000180', '地勘单位资质', 'DKDWZZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000181', '房间使用状态', 'FJSYZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000182', '房间用途', 'FJYT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000183', '房屋产权', 'FWCQ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000184', '房屋类型', 'FWLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000185', '管理级别', 'GLJB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000186', '供暖方式', 'GNFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000187', '供水情况', 'GSQK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000188', '获得方式', 'HDFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000189', '监理单位资质', 'JLDWZZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000190', '建筑物安全排查结论', 'JZWAQPCJL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000191', '建筑物分类', 'JZWFL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000192', '建筑物基础形式', 'JZWJCXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000193', '建筑物鉴定内容', 'JZWJDNR', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000194', '建筑物结构', 'JZWJG', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000195', '建筑物楼板形式', 'JZWLBXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000196', '建筑物平面形式', 'JZWPMXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000197', '建筑物用途', 'JZWYT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000198', '建筑物状况', 'JZWZK', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000199', '刊物装订', 'KWZD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000200', '抗震设防标准', 'KZSFBZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000201', '抗震设防烈度', 'KZSFLD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000202', '取得方式', 'QDFS', '0', '0', '本代码规定了土地的取得方式');
INSERT INTO `uc_dict_type` VALUES ('jg00000203', '施工单位资质等级', 'SGDWZZDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000204', '施工图纸审查资质', 'SGTZSCZZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000205', '设计单位资质', 'SJDWZZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000206', '实验室费用', 'SYSFY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000207', '实验室类别', 'SYSLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000208', '实验室评估结果', 'SYSPGJG', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000209', '使用状况', 'SYZK', '0', '0', '本代码规定了中小学校、中等职业学校、高等学校和幼儿园资产使用状况的分类');
INSERT INTO `uc_dict_type` VALUES ('jg00000210', '图书期刊状态', 'TSQKZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000211', '文物建筑等级', 'WWJZDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000212', '消防耐火等级', 'XFNHDJ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000213', '仪器使用方向', 'YQSYFX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000214', '仪器现状', 'YQXZ', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000215', '占地用途', 'ZDYT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000216', '招投标形式', 'ZTBXS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000217', '中央专项投资补助名称', 'ZYZXTZBZMC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000218', '财务票类', 'CWPL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000219', '分录标记', 'FLBJ', '0', '0', '本代码规定了高等学校财务管理的借贷分类');
INSERT INTO `uc_dict_type` VALUES ('jg00000220', '服务业发票规格', 'FWYFPGG', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000221', '经费科目', 'JFKM', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000222', '经费来源', 'JFLY', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000223', '记账方向', 'JZFX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000224', '科目级次', 'KMJC', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000225', '科目类别', 'KMLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000226', '预算类总分类科目', 'YSLZFLKM', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000227', '档案保管期限', 'DABGQX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000228', '档案稿本', 'DAGB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000229', '档案关系', 'DAGX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000230', '档案关系类型', 'DAGXLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000231', '档案文件组合类型', 'DAWJZHLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000232', '档案业务行为', 'DAYWXW', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000233', '电子文件类型', 'DZWJLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000234', '发送方式', 'FSFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000235', '反映问题类别', 'FYWTLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000236', '公开方式', 'GKFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000237', '公文封装方式', 'GWFZFS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000238', '公文状态', 'GWZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000239', '紧急程度', 'JJCD', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000240', '纪要状态', 'JYZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000241', '色彩模式', 'SCMS', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000242', '文件分类', 'WJFL', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000243', '阅办类别', 'YBLB', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000244', '议题状态', 'YTZT', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000245', '载体类型', 'ZTLX', '0', '0', '');
INSERT INTO `uc_dict_type` VALUES ('jg00000246', '专业技术职务', 'ZYJSZW', '0', '0', null);
INSERT INTO `uc_dict_type` VALUES ('zxx0000001', '中小学学科', 'ZXXXK', '0', '0', '中小学学科表');

-- ----------------------------
-- Table structure for uc_domains
-- ----------------------------
DROP TABLE IF EXISTS `uc_domains`;
CREATE TABLE `uc_domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` char(40) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_domains
-- ----------------------------

-- ----------------------------
-- Table structure for uc_failedlogins
-- ----------------------------
DROP TABLE IF EXISTS `uc_failedlogins`;
CREATE TABLE `uc_failedlogins` (
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_failedlogins
-- ----------------------------

-- ----------------------------
-- Table structure for uc_feeds
-- ----------------------------
DROP TABLE IF EXISTS `uc_feeds`;
CREATE TABLE `uc_feeds` (
  `feedid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(30) NOT NULL DEFAULT '',
  `icon` varchar(30) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `hash_template` varchar(32) NOT NULL DEFAULT '',
  `hash_data` varchar(32) NOT NULL DEFAULT '',
  `title_template` text NOT NULL,
  `title_data` text NOT NULL,
  `body_template` text NOT NULL,
  `body_data` text NOT NULL,
  `body_general` text NOT NULL,
  `image_1` varchar(255) NOT NULL DEFAULT '',
  `image_1_link` varchar(255) NOT NULL DEFAULT '',
  `image_2` varchar(255) NOT NULL DEFAULT '',
  `image_2_link` varchar(255) NOT NULL DEFAULT '',
  `image_3` varchar(255) NOT NULL DEFAULT '',
  `image_3_link` varchar(255) NOT NULL DEFAULT '',
  `image_4` varchar(255) NOT NULL DEFAULT '',
  `image_4_link` varchar(255) NOT NULL DEFAULT '',
  `target_ids` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`feedid`),
  KEY `uid` (`uid`,`dateline`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_feeds
-- ----------------------------

-- ----------------------------
-- Table structure for uc_friends
-- ----------------------------
DROP TABLE IF EXISTS `uc_friends`;
CREATE TABLE `uc_friends` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `friendid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delstatus` tinyint(1) NOT NULL DEFAULT '0',
  `comment` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`version`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `friendid` (`friendid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_friends
-- ----------------------------

-- ----------------------------
-- Table structure for uc_gradeinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_gradeinfo`;
CREATE TABLE `uc_gradeinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xxid` int(11) DEFAULT NULL,
  `jd` int(11) DEFAULT NULL,
  `nj` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `njmc` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of uc_gradeinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_group
-- ----------------------------
DROP TABLE IF EXISTS `uc_group`;
CREATE TABLE `uc_group` (
  `GroupID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` char(50) NOT NULL DEFAULT '',
  `GroupDept` char(50) NOT NULL DEFAULT '',
  `UpGroupID` mediumint(8) NOT NULL DEFAULT '0',
  `GroupManager` char(20) NOT NULL DEFAULT '',
  `GroupPropt` char(50) NOT NULL DEFAULT '',
  `Remark` varchar(255) NOT NULL,
  PRIMARY KEY (`GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_group
-- ----------------------------

-- ----------------------------
-- Table structure for uc_group_member
-- ----------------------------
DROP TABLE IF EXISTS `uc_group_member`;
CREATE TABLE `uc_group_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` char(32) NOT NULL DEFAULT '',
  `GroupID` mediumint(8) NOT NULL DEFAULT '0',
  `Remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_group_member
-- ----------------------------

-- ----------------------------
-- Table structure for uc_identity
-- ----------------------------
DROP TABLE IF EXISTS `uc_identity`;
CREATE TABLE `uc_identity` (
  `IdentityID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `IdentityName` char(50) NOT NULL DEFAULT '',
  `isreg` tinyint(1) NOT NULL DEFAULT '0',
  `IdentityIcon` char(50) NOT NULL DEFAULT '',
  `order` int(11) DEFAULT '0',
  `Remark` varchar(255) NOT NULL,
  PRIMARY KEY (`IdentityID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_identity
-- ----------------------------
INSERT INTO `uc_identity` VALUES ('3', '学生', '1', './images/upidentityfile/1351248326.png', '0', '');
INSERT INTO `uc_identity` VALUES ('1', '管理员', '-1', './images/upidentityfile/1351248326.png', '0', '');
INSERT INTO `uc_identity` VALUES ('2', '老师', '1', './images/upidentityfile/1351248326.png', '1', '');
INSERT INTO `uc_identity` VALUES ('5', '校友', '0', './images/upidentityfile/1351248326.png', '0', '');

-- ----------------------------
-- Table structure for uc_jbzd
-- ----------------------------
DROP TABLE IF EXISTS `uc_jbzd`;
CREATE TABLE `uc_jbzd` (
  `jbid` int(11) NOT NULL,
  `jbzd` varchar(10) NOT NULL,
  PRIMARY KEY (`jbid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_jbzd
-- ----------------------------

-- ----------------------------
-- Table structure for uc_mailqueue
-- ----------------------------
DROP TABLE IF EXISTS `uc_mailqueue`;
CREATE TABLE `uc_mailqueue` (
  `mailid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tomail` varchar(32) NOT NULL,
  `frommail` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `charset` varchar(15) NOT NULL,
  `htmlon` tinyint(1) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `failures` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mailid`),
  KEY `appid` (`appid`) USING BTREE,
  KEY `level` (`level`,`failures`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_mailqueue
-- ----------------------------

-- ----------------------------
-- Table structure for uc_memberfields
-- ----------------------------
DROP TABLE IF EXISTS `uc_memberfields`;
CREATE TABLE `uc_memberfields` (
  `uid` mediumint(8) unsigned NOT NULL,
  `blacklist` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_memberfields
-- ----------------------------
INSERT INTO `uc_memberfields` VALUES ('1', '');

-- ----------------------------
-- Table structure for uc_members
-- ----------------------------
DROP TABLE IF EXISTS `uc_members`;
CREATE TABLE `uc_members` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` char(32) NOT NULL DEFAULT '',
  `RealName` char(32) NOT NULL DEFAULT '',
  `RoleID` mediumint(8) NOT NULL DEFAULT '0',
  `DeptID` mediumint(8) NOT NULL DEFAULT '0',
  `UserNO` char(20) NOT NULL DEFAULT '',
  `myid` char(30) NOT NULL DEFAULT '',
  `myidkey` char(16) NOT NULL DEFAULT '',
  `regip` char(15) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` int(10) NOT NULL DEFAULT '0',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) NOT NULL,
  `secques` char(8) NOT NULL DEFAULT '',
  `identityID` mediumint(8) NOT NULL,
  `identityType` char(2) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `email` (`email`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_members
-- ----------------------------
INSERT INTO `uc_members` VALUES ('1', '管理员', '6655505db4136c5bcde3db1cb9fe6b41', 'admin@admin.com', '', '0', '1', '', '', '', '127.0.0.1', '1343790567', '0', '0', '7eaf08', '', '1', '1');

-- ----------------------------
-- Table structure for uc_menu
-- ----------------------------
DROP TABLE IF EXISTS `uc_menu`;
CREATE TABLE `uc_menu` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `privacy` varchar(50) NOT NULL DEFAULT '',
  `mod` varchar(50) NOT NULL DEFAULT '',
  `func` varchar(50) NOT NULL DEFAULT '',
  `odr` int(10) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_menu
-- ----------------------------

-- ----------------------------
-- Table structure for uc_mergemembers
-- ----------------------------
DROP TABLE IF EXISTS `uc_mergemembers`;
CREATE TABLE `uc_mergemembers` (
  `appid` smallint(6) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  PRIMARY KEY (`appid`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_mergemembers
-- ----------------------------

-- ----------------------------
-- Table structure for uc_newpm
-- ----------------------------
DROP TABLE IF EXISTS `uc_newpm`;
CREATE TABLE `uc_newpm` (
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_newpm
-- ----------------------------

-- ----------------------------
-- Table structure for uc_notelist
-- ----------------------------
DROP TABLE IF EXISTS `uc_notelist`;
CREATE TABLE `uc_notelist` (
  `noteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operation` char(32) NOT NULL,
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `totalnum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `succeednum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `getdata` mediumtext NOT NULL,
  `postdata` mediumtext NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pri` tinyint(3) NOT NULL DEFAULT '0',
  `app1` tinyint(4) NOT NULL,
  PRIMARY KEY (`noteid`),
  KEY `closed` (`closed`,`pri`,`noteid`) USING BTREE,
  KEY `dateline` (`dateline`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_notelist
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pms
-- ----------------------------
DROP TABLE IF EXISTS `uc_pms`;
CREATE TABLE `uc_pms` (
  `pmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msgfrom` varchar(15) NOT NULL DEFAULT '',
  `msgfromid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `msgtoid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `folder` enum('inbox','outbox') NOT NULL DEFAULT 'inbox',
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `subject` varchar(75) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `related` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `msgtoid` (`msgtoid`,`folder`,`dateline`) USING BTREE,
  KEY `msgfromid` (`msgfromid`,`folder`,`dateline`) USING BTREE,
  KEY `related` (`related`) USING BTREE,
  KEY `getnum` (`msgtoid`,`folder`,`delstatus`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of uc_pms
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pm_indexes
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_indexes`;
CREATE TABLE `uc_pm_indexes` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_indexes
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pm_lists
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_lists`;
CREATE TABLE `uc_pm_lists` (
  `plid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pmtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(80) NOT NULL,
  `members` smallint(5) unsigned NOT NULL DEFAULT '0',
  `min_max` varchar(17) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmessage` text NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `pmtype` (`pmtype`) USING BTREE,
  KEY `min_max` (`min_max`) USING BTREE,
  KEY `authorid` (`authorid`,`dateline`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_lists
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pm_members
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_members`;
CREATE TABLE `uc_pm_members` (
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `isnew` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pmnum` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`plid`,`uid`),
  KEY `isnew` (`isnew`) USING BTREE,
  KEY `lastdateline` (`uid`,`lastdateline`) USING BTREE,
  KEY `lastupdate` (`uid`,`lastupdate`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_members
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pm_messages_0
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_0`;
CREATE TABLE `uc_pm_messages_0` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`) USING BTREE,
  KEY `dateline` (`plid`,`dateline`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_0
-- ----------------------------

-- ----------------------------
-- Table structure for uc_pm_messages_1
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_1`;
CREATE TABLE `uc_pm_messages_1` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`) USING BTREE,
  KEY `dateline` (`plid`,`dateline`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_1
-- ----------------------------

-- ----------------------------
-- Table structure for uc_protectedmembers
-- ----------------------------
DROP TABLE IF EXISTS `uc_protectedmembers`;
CREATE TABLE `uc_protectedmembers` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(15) NOT NULL DEFAULT '',
  `appid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `admin` char(15) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`,`appid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_protectedmembers
-- ----------------------------

-- ----------------------------
-- Table structure for uc_role
-- ----------------------------
DROP TABLE IF EXISTS `uc_role`;
CREATE TABLE `uc_role` (
  `RoleID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `RoleName` char(50) NOT NULL DEFAULT '',
  `isreg` tinyint(1) NOT NULL DEFAULT '0',
  `RoleIcon` char(50) NOT NULL DEFAULT '',
  `ridentityid` int(11) DEFAULT NULL,
  `Remark` varchar(255) NOT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_role
-- ----------------------------
INSERT INTO `uc_role` VALUES ('24', '广场管理员', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('23', '应用管理员', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('22', '内容管理员', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('21', '系统管理员', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('20', '超级管理员', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('36', '校友', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('37', '普通学生用户', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('38', '普通老师用户', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('39', '应用测试', '0', '', null, '');
INSERT INTO `uc_role` VALUES ('40', '社团管理员', '0', '', null, '');

-- ----------------------------
-- Table structure for uc_roster_student
-- ----------------------------
DROP TABLE IF EXISTS `uc_roster_student`;
CREATE TABLE `uc_roster_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(100) DEFAULT NULL,
  `seatno` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `userno` varchar(100) NOT NULL,
  `identity` varchar(18) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `oldname` varchar(100) DEFAULT NULL,
  `nation` varchar(50) DEFAULT NULL,
  `comefrom` varchar(100) DEFAULT NULL,
  `studying` varchar(100) DEFAULT NULL,
  `studenttype` varchar(100) DEFAULT NULL,
  `familystatus` varchar(100) DEFAULT NULL,
  `lowfamilycard` varchar(18) DEFAULT NULL,
  `lowfamilytype` varchar(50) DEFAULT NULL,
  `household` varchar(100) DEFAULT NULL,
  `housecity` varchar(100) DEFAULT NULL,
  `houseaddress` varchar(255) DEFAULT NULL,
  `postalcode` varchar(6) DEFAULT NULL,
  `nowaddress` varchar(255) DEFAULT NULL,
  `nativeplace` varchar(100) DEFAULT NULL,
  `ismigrantworkers` tinyint(1) NOT NULL DEFAULT '0',
  `migrantworkerstype` varchar(50) DEFAULT NULL,
  `migrantworkersfrom` varchar(100) DEFAULT NULL,
  `isleftbehind` tinyint(1) NOT NULL DEFAULT '0',
  `leftbehindsituation` varchar(255) DEFAULT NULL,
  `leftbehindentrust` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `countryother` varchar(50) DEFAULT NULL,
  `certificatesno` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `guardianname` varchar(20) DEFAULT NULL,
  `father` varchar(20) DEFAULT NULL,
  `mother` varchar(20) DEFAULT NULL,
  `fatherwork` varchar(200) DEFAULT NULL,
  `motherwork` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seatno` (`seatno`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_roster_student
-- ----------------------------

-- ----------------------------
-- Table structure for uc_roster_teacher
-- ----------------------------
DROP TABLE IF EXISTS `uc_roster_teacher`;
CREATE TABLE `uc_roster_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(100) DEFAULT NULL,
  `seatno` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `userno` varchar(100) NOT NULL,
  `identity` varchar(18) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `oldname` varchar(100) DEFAULT NULL,
  `nation` varchar(50) DEFAULT NULL,
  `comefrom` varchar(100) DEFAULT NULL,
  `studying` varchar(100) DEFAULT NULL,
  `studenttype` varchar(100) DEFAULT NULL,
  `familystatus` varchar(100) DEFAULT NULL,
  `lowfamilycard` varchar(18) DEFAULT NULL,
  `lowfamilytype` varchar(50) DEFAULT NULL,
  `household` varchar(100) DEFAULT NULL,
  `housecity` varchar(100) DEFAULT NULL,
  `houseaddress` varchar(255) DEFAULT NULL,
  `postalcode` varchar(6) DEFAULT NULL,
  `nowaddress` varchar(255) DEFAULT NULL,
  `nativeplace` varchar(100) DEFAULT NULL,
  `ismigrantworkers` tinyint(1) NOT NULL DEFAULT '0',
  `migrantworkerstype` varchar(50) DEFAULT NULL,
  `migrantworkersfrom` varchar(100) DEFAULT NULL,
  `isleftbehind` tinyint(1) NOT NULL DEFAULT '0',
  `leftbehindsituation` varchar(255) DEFAULT NULL,
  `leftbehindentrust` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `countryother` varchar(50) DEFAULT NULL,
  `certificatesno` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `guardianname` varchar(20) DEFAULT NULL,
  `father` varchar(20) DEFAULT NULL,
  `mother` varchar(20) DEFAULT NULL,
  `fatherwork` varchar(200) DEFAULT NULL,
  `motherwork` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seatno` (`seatno`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_roster_teacher
-- ----------------------------

-- ----------------------------
-- Table structure for uc_r_identity_role
-- ----------------------------
DROP TABLE IF EXISTS `uc_r_identity_role`;
CREATE TABLE `uc_r_identity_role` (
  `identityid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '身份id.身份id，对应uc_identity表中的IdentityID',
  `roleid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '角色ID.角色id，对应uc_role 表中的RoleID',
  `sortorder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`identityid`,`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='身份角色对应表, 该表定义身份与角色的对应关系。一个身份可以对应多个角色。';

-- ----------------------------
-- Records of uc_r_identity_role
-- ----------------------------

-- ----------------------------
-- Table structure for uc_r_role_func
-- ----------------------------
DROP TABLE IF EXISTS `uc_r_role_func`;
CREATE TABLE `uc_r_role_func` (
  `RoleID` mediumint(8) NOT NULL COMMENT '角色id，对应uc_role 表中的RoleID',
  `App_name` varchar(255) NOT NULL COMMENT '应用名称，对应ts_app表中的app_name字段',
  `App_id` int(11) DEFAULT NULL COMMENT '预留。由于ts_app表中app_id字段是主键，但应用中未使用该字段做为关联，这里保留该关联字段以便改动',
  `RoleExtend` varchar(255) DEFAULT NULL COMMENT '用于对角色权限的进一步控制。如可设定为add=true;del=false;通过该属性与展示层代码的配合，完成权限的详细控制',
  `SortOrder` int(11) DEFAULT NULL COMMENT '排列顺序时的序号',
  `Remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`RoleID`,`App_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色功能点对应表, 该表定义角色与应用的对应关系。一个角色可以对应多个功能点（应用）。';

-- ----------------------------
-- Records of uc_r_role_func
-- ----------------------------
INSERT INTO `uc_r_role_func` VALUES ('20', 'society', '1', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'photo', '3', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'event', '4', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'vote', '5', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'timeline', '6', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'gift', '7', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'desktop', '9', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'favorite', '11', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'tool', '14', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('20', 'club', '21', '', '0', null, '2014-06-25 10:43:53');
INSERT INTO `uc_r_role_func` VALUES ('40', 'club', '21', 'I,', '0', null, '2014-06-25 10:47:37');
INSERT INTO `uc_r_role_func` VALUES ('38', 'vote', '5', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'club', '21', '', '0', null, '2014-06-25 11:08:25');
INSERT INTO `uc_r_role_func` VALUES ('38', 'photo', '3', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'event', '4', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'society', '1', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'gift', '7', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'timeline', '6', 'I,D,U,S,', '0', null, '2014-06-25 11:17:31');
INSERT INTO `uc_r_role_func` VALUES ('38', 'tool', '14', '', '0', null, '2014-06-25 11:10:42');
INSERT INTO `uc_r_role_func` VALUES ('38', 'favorite', '11', '', '0', null, '2014-06-25 11:13:17');
INSERT INTO `uc_r_role_func` VALUES ('37', 'favorite', '11', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'society', '1', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'timeline', '6', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'gift', '7', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'tool', '14', '', '0', null, '2014-06-25 11:20:36');
INSERT INTO `uc_r_role_func` VALUES ('37', 'event', '4', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'photo', '3', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'vote', '5', 'I,D,U,S,', '0', null, '2014-06-25 11:30:10');
INSERT INTO `uc_r_role_func` VALUES ('37', 'club', '21', '', '0', null, '2014-06-25 11:20:51');
INSERT INTO `uc_r_role_func` VALUES ('21', 'vote', '5', '', '0', null, '2014-06-25 11:22:30');
INSERT INTO `uc_r_role_func` VALUES ('21', 'photo', '3', '', '0', null, '2014-06-25 11:22:30');
INSERT INTO `uc_r_role_func` VALUES ('21', 'event', '4', '', '0', null, '2014-06-25 11:22:39');
INSERT INTO `uc_r_role_func` VALUES ('21', 'tool', '14', '', '0', null, '2014-06-25 11:22:39');
INSERT INTO `uc_r_role_func` VALUES ('22', 'event', '4', '', '0', null, '2014-06-25 11:23:20');
INSERT INTO `uc_r_role_func` VALUES ('22', 'photo', '3', '', '0', null, '2014-06-25 11:23:20');
INSERT INTO `uc_r_role_func` VALUES ('23', 'job', '23', '', '0', null, '2014-06-25 11:25:14');
INSERT INTO `uc_r_role_func` VALUES ('23', 'vote', '5', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('23', 'photo', '3', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('23', 'event', '4', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('23', 'gift', '7', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('23', 'favorite', '11', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('23', 'video', '2', 'A,', '0', null, '2014-06-25 11:25:31');
INSERT INTO `uc_r_role_func` VALUES ('36', 'event', '4', 'S,', '0', null, '2014-06-25 11:26:21');
INSERT INTO `uc_r_role_func` VALUES ('36', 'video', '2', '', '0', null, '2014-06-25 11:26:08');
INSERT INTO `uc_r_role_func` VALUES ('36', 'photo', '3', 'S,', '0', null, '2014-06-25 11:26:21');
INSERT INTO `uc_r_role_func` VALUES ('36', 'vote', '5', '', '0', null, '2014-06-25 11:26:08');
INSERT INTO `uc_r_role_func` VALUES ('20', 'tool_square', '25', '', '0', null, '2014-06-25 13:55:07');
INSERT INTO `uc_r_role_func` VALUES ('20', 'article_square', '29', '', '0', null, '2014-06-25 13:55:07');

-- ----------------------------
-- Table structure for uc_r_teacher_class
-- ----------------------------
DROP TABLE IF EXISTS `uc_r_teacher_class`;
CREATE TABLE `uc_r_teacher_class` (
  `identityid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '老师身份ID.用户id，对应uc_members表中的identityID',
  `classid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '班级ID.班级id，对应班级表中的id',
  `classno` varchar(50) DEFAULT NULL COMMENT '班级编号.班级id，对应班级表中的bh',
  `courseid` varchar(8) NOT NULL DEFAULT '' COMMENT '课程ID.老师在该班级教授的课程',
  `dutyid` varchar(8) NOT NULL DEFAULT '' COMMENT '职务.老师在该班级担任的职务,如班主任,任课老师等',
  `sortorder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`identityid`,`classid`,`courseid`,`dutyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='老师班级对应表, 该表定义老师与所教班级的对应关系。一位老师可以任多个班级的课程。';

-- ----------------------------
-- Records of uc_r_teacher_class
-- ----------------------------

-- ----------------------------
-- Table structure for uc_r_teacher_course
-- ----------------------------
DROP TABLE IF EXISTS `uc_r_teacher_course`;
CREATE TABLE `uc_r_teacher_course` (
  `identityid` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '老师身份ID.用户id，对应uc_members表中的identityID',
  `courseid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '课程ID.课程id，对应课程表中的CourseID',
  `sortorder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`identityid`,`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='老师任课表, 该表定义老师与所教课程的对应关系。一位老师可以任多个课程。';

-- ----------------------------
-- Records of uc_r_teacher_course
-- ----------------------------

-- ----------------------------
-- Table structure for uc_r_user_role
-- ----------------------------
DROP TABLE IF EXISTS `uc_r_user_role`;
CREATE TABLE `uc_r_user_role` (
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户id.用户id，对应uc_members表中的uid',
  `roleid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '角色id.角色id，对应uc_role 表中的roleid',
  `sortorder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`uid`,`roleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色对应表, 该表定义用户与角色的对应关系。一个用户可以对应多个角色。';

-- ----------------------------
-- Records of uc_r_user_role
-- ----------------------------

-- ----------------------------
-- Table structure for uc_schoolfellow
-- ----------------------------
DROP TABLE IF EXISTS `uc_schoolfellow`;
CREATE TABLE `uc_schoolfellow` (
  `identityid` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '身份id.',
  `xm` varchar(20) DEFAULT NULL COMMENT '姓名.',
  `ywxm` varchar(20) DEFAULT NULL COMMENT '英文姓名.',
  `xmpy` varchar(40) DEFAULT NULL COMMENT '姓名拼音.',
  `xbm` char(1) DEFAULT NULL COMMENT '性别码.',
  `lxdh` varchar(20) DEFAULT NULL COMMENT '联系电话.本人的联系电话号码',
  `dzxx` varchar(40) DEFAULT NULL COMMENT '电子信箱.在互联网上的电子邮件信箱地址',
  `bysj` varchar(8) DEFAULT NULL COMMENT '毕业时间.',
  `xslbm` char(2) DEFAULT NULL COMMENT '学生类别码.21小学生；31初中生；32高中生；33中职学生；34工读学生；41专科生；42本科生；43研究生；44学位学生',
  `classid` varchar(40) DEFAULT NULL COMMENT '班级id.毕业时所在班级id',
  `sfzjlxm` char(2) DEFAULT NULL COMMENT '身份证件类型码.',
  `sfzjh` varchar(20) DEFAULT NULL COMMENT '身份证件号.',
  `hyzkm` char(1) DEFAULT NULL COMMENT '婚姻状况码.',
  `gatqwm` char(2) DEFAULT NULL COMMENT '港澳台侨外码.',
  `txdz` varchar(100) DEFAULT NULL COMMENT '通信地址.',
  `yzbm` varchar(6) DEFAULT NULL COMMENT '邮政编码.通信地址的邮政编码',
  `jtzz` varchar(100) DEFAULT NULL COMMENT '家庭住址.指包括省（自治区、直辖市）/地（市、州）/县（区、旗）/乡（镇）/街（村）的详细地址',
  `xzz` varchar(100) DEFAULT NULL COMMENT '现住址.指本人当前的常住地址',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户id',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户id',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n00.有效 99.已删除',
  PRIMARY KEY (`identityid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='校友信息表, 该表定义校友的基本信息';

-- ----------------------------
-- Records of uc_schoolfellow
-- ----------------------------

-- ----------------------------
-- Table structure for uc_schoolinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_schoolinfo`;
CREATE TABLE `uc_schoolinfo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `xxdm` varchar(50) NOT NULL COMMENT '学校代码',
  `xxmc` varchar(50) NOT NULL COMMENT '学校名称',
  `xxywmc` varchar(50) NOT NULL COMMENT '学校英文名称',
  `xxdz` varchar(100) NOT NULL COMMENT '学校地址',
  `xxyzbm` varchar(25) NOT NULL COMMENT '学校邮政编码',
  `xzqhm` varchar(25) NOT NULL COMMENT '行政区划码',
  `jxny` varchar(4) NOT NULL COMMENT '建校年月',
  `xqr` varchar(8) NOT NULL COMMENT '校庆日',
  `xxbxlxm` varchar(50) NOT NULL COMMENT '学校办学类型码',
  `xxzgbmm` varchar(50) NOT NULL COMMENT '学校主管部门码',
  `fddbrh` varchar(50) NOT NULL COMMENT '法定代表人号',
  `frzsh` varchar(50) NOT NULL COMMENT '法人证书号',
  `xzgh` varchar(50) NOT NULL COMMENT '校长工号',
  `xzxm` varchar(50) NOT NULL COMMENT '校长姓名',
  `dwfzrh` varchar(50) NOT NULL COMMENT '党委负责人号',
  `zzjgm` varchar(50) NOT NULL COMMENT '组织机构码',
  `lxdh` varchar(50) NOT NULL COMMENT '联系电话',
  `czdh` varchar(50) NOT NULL COMMENT '传真电话',
  `dzxx` varchar(50) NOT NULL COMMENT '电子信箱',
  `zydz` varchar(100) NOT NULL COMMENT '主页地址',
  `lsyg` varchar(100) NOT NULL COMMENT '历史沿革',
  `xxbbm` varchar(50) NOT NULL COMMENT '学校办别码',
  `sszgdwm` varchar(50) NOT NULL COMMENT '所属主管单位码',
  `szdcxlxm` varchar(50) NOT NULL COMMENT '所在地城乡类型码',
  `szdjjsxm` varchar(50) NOT NULL COMMENT '所在地经济属性码',
  `szdmzsx` varchar(50) NOT NULL COMMENT '所在地民族属性',
  `zjxyym` varchar(50) NOT NULL COMMENT '主教学语言码',
  `fjxyym` varchar(50) NOT NULL COMMENT '辅教学语言码',
  `zsbj` varchar(100) NOT NULL COMMENT '招生半径',
  `xxxzm` int(11) DEFAULT NULL,
  `xxjbzm` int(11) DEFAULT NULL,
  `xx211gczk` varchar(255) DEFAULT NULL,
  `985gcyxzk` varchar(255) DEFAULT NULL,
  `zdxxzk` varchar(255) DEFAULT NULL,
  `yjsyzk` varchar(255) DEFAULT NULL,
  `jbwljyzk` varchar(255) DEFAULT NULL,
  `jbcrjyzk` varchar(255) DEFAULT NULL,
  `xkmls` varchar(255) DEFAULT NULL,
  `gjsfxgzyxzk` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `isdel` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_schoolinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_service
-- ----------------------------
DROP TABLE IF EXISTS `uc_service`;
CREATE TABLE `uc_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `serviceID` varchar(50) DEFAULT NULL COMMENT '服务标识id.',
  `class` varchar(200) NOT NULL COMMENT '服务类名.',
  `method` varchar(100) NOT NULL COMMENT '服务方法名.',
  `serviceDes` varchar(100) DEFAULT NULL COMMENT '服务说明.',
  `serviceFlag` char(2) DEFAULT '00' COMMENT '服务状态标识.00:有效;01:关闭',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n00.有效 99.已删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `serviceID` (`serviceID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COMMENT='统一身份认证服务列表, 该表定义统一身份认证系统对外提供的webService服务列表';

-- ----------------------------
-- Records of uc_service
-- ----------------------------
INSERT INTO `uc_service` VALUES ('1', 'Usch002', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchool2Class', '根据获取班级列表树', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('2', 'Ustu004', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentsByClassid', '根据班级id获取学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('3', 'Usch003', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSubjectBySchoolid', '根据学校id获取学科信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('4', 'Usch004', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchoolXdSubjectNj', '根据学校id获取学校-学段-学科-年级结构', '01', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('5', 'Utch006', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getLeader', '根据学校、(职务类型、学段、科目、年级)获取(分管副校长、教研组组长、备课组组长）信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('6', 'Utch007', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherByGrade', '根据学校、学段、科目、年级获取所有老师信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('7', 'Uuse001', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserByUid', '根据用户ID获取用户信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('8', 'Usch005', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getXdBySchool', '根据学校获取学段列表信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('9', 'Usch006', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getNJBySchoolXD', '根据学校、学段获取级部信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('10', 'Uuse003', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserByAppLimit', '查询有某项功能某项权限的用户', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('11', 'Usch007', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSubjectBySchoolXdNj', '根据学校、学段、年级编码获取学科', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('12', 'Ustu001', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentDetail', '根据学生uid获取学生详细信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('13', 'Ustu003', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentinfoByParentid', '根据家长id获取学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('14', 'Utch001', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getUserinfoByUserno', '根据基础数据中的老师身份证号获取老师的基础信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('15', 'Usch001', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getOrganizationBySchoolid', '根据学校ID获取组织机构', '01', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('16', 'Ustu002', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getParentinfoByStudentid', '根据学生id获取家长信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('17', 'Utch002', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherById', '根据基础数据中的ID号获取教师的基础信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('18', 'Usch008', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getCourseListBySchoolid', '根据学校id获取课程列表', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('19', 'Uuse004', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserinfoByUid', '根据用户id查询用户角色id、用户identityid、学校id、班级id（学生）、identityidType', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('20', 'Usch009', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchoolByXd', '根据学段查找学校', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('21', 'Utch008', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherUidByIdentityid', '根据老师identityid获取学校、年级、学科相同的老师uid信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('22', 'Uuse002', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserAppLimitByUid', '根据用户uid查询用户的权限信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('23', 'Utch009', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getClassByTeacherUid', '根据老师uid查询该老师所教班级的班级id和班级名称', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('24', 'Utch010', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getAllClassByTeacherUid', '根据老师uid查询该老师所在学校所有班级的班级id和班级名称', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('25', 'Usch010', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchoolById', '根据学校id获取学校信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('26', 'Usch011', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getXdBySchoolid', '根据学校id获取学段', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('27', 'Usch012', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getGradeBySchoolidXd', '根据学校id，学段获取年级', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('28', 'Usch013', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getClassByJb', '根据级部id，获取班级', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('29', 'Usch014', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getKmByjb', '根据级部id，获取科目', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('30', 'Uuse005', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getEmailByIdentityid', '根据identityid，获取会员email', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('31', 'Ustu005', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentnameBySchoolidClassid', '根据学校id、班级id获取学生姓名', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('32', 'Usch015', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getClassByTeacherUid', '根据老师的uid，获取老师所在学校的班级', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('33', 'Usch016', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getXdSchoolSubjectGrade', '获取所有学校的学段、学校、科目、年级树', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('34', 'Usch017', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getClassById', '根据班级id获取班级信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('35', 'Usch018', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSubjectById', '根据科目id获取科目信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('36', 'Usch019', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAllSubjectBySchoolid', '根据学校id获取所有学科信息，不同年级相同学科不算一个学科', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('37', 'Utch011', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getBkzTeacherByTeacherId', '根据老师identityid获取该备课组成员', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('38', 'Utch012', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getZzTeacherByTeacherId', '根据老师identityid查询该教研组长和备课组长', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('39', 'Usch020', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getGradeById', '根据级部id获取级部信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('40', 'IM003', 'com.gridsoft.encwebsvc.ws.dao.impl.IMDao', 'getAllClassMembers', '获取所有班级的所有学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('41', 'IM002', 'com.gridsoft.encwebsvc.ws.dao.impl.IMDao', 'getAllClassAndGroup', '获取所有班级信息和所有教研组信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('42', 'Usch024', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getTeacherGroupBySchoolAndXd', '根据学校ID和学段查询该校、该学段的所有教研组', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('43', 'Uuse007', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getPasswordByEmail', '根据用户email获取用户密码', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('44', 'Uuse008', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'authenticate', '通过用户email验证用户密码', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('45', 'Ustu016', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentCount', '根据班级id获取班级男女人数', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('46', 'Usch021', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getGradeByClassid', '根据班级id查找级部信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('47', 'Uuse006', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUsersInfoByUid', '根据用户uid获取用户username、identityId、email', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('48', 'Usch022', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getXdSubjectGrade', '获取学段，科目，年级组织结构', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('49', 'Usch023', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getNjBySchoolidXdKm', '根据学校id、学段、科目获取年级信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('50', 'IM001', 'com.gridsoft.encwebsvc.ws.dao.impl.IMDao', 'getUserAllInfoByUid', '根据uid获取用户的组织结构', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('51', 'IM004', 'com.gridsoft.encwebsvc.ws.dao.impl.IMDao', 'getAllGroupTeacher', '获取所有教研组的所有老师信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('52', 'Ustu006', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentByRealName', '根据学生真实姓名查找用户所有信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('53', 'Ustu007', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentByRealNameAndParentName', '根据学生真实姓名及家长姓名查找用户所有信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('54', 'IM005', 'com.gridsoft.encwebsvc.ws.dao.impl.IMDao', 'getAllMembers', '获取所有用户信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('55', 'Ustu008', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentInfoByXh', '根据学生学号获取学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('56', 'Usch025', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAllSchool', '获取所有学校信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('57', 'Ustu009', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentBySchoolClass', '根据（学校、班级）获取所有学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('58', 'Ustu010', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentInfo', '不定参数查询学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('59', 'Utch026', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherInfo', '不定参数查询教师信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('60', 'Uuse009', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getParentAndChildInfo', '不定参数查询家长以及家长的孩子信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('61', 'Uuse010', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserInfoByJudgeIdentity', '判断用户身份（学生、教师、家长），返回对应的用户信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('62', 'Utch013', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getRegisterTeacherBySchoolId', '根据学校ID获取该学校下已注册的老师信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('63', 'Ustu011', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getRegisterStudentBySchoolId', '根据学校ID获取该学校下已注册的学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('64', 'Ustu012', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getRegisterStudentByClassId', '根据班级ID获取该班级下已注册的学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('65', 'Uuse011', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getRegisterParentBySchoolId', '根据学校ID获取该学校下已注册的家长信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('66', 'Uuse012', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getRegisterParentByClassId', '根据班级ID获取该班级下已注册的家长信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('67', 'Uuse013', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getParentInfo', '不定参数查询家长信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('68', 'Usch026', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchoolAndSubject', '通过学校名称和科目名称模糊查询学校id、学校名称、科目id、科目名称', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('69', 'Uuse014', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getMemberInfo', '不定参数查询用户信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('70', 'Uuse015', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'deleteMemberByEmailOrUid', '根据用户uid或者用户email删除用户', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('71', 'Ustu013', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getClassinfoByStudentUid', '根据学生uid查询学生的班级信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('72', 'Log001', 'com.gridsoft.encwebsvc.ws.dao.impl.LogDao', 'getAllServiceLogOrderByTime', '通过时间排序 查询service日志', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('73', 'Ustu014', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getClassByParentIdentityid', '根据家长identityid获取孩子班级信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('74', 'Uuse016', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'updateUserByUid', '根据用户uid不定项修改用户信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('75', 'Uuse017', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'addMember', '新增用户', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('76', 'Usch027', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getClassBySchoolId', '通过学校id获取班级', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('77', 'Cache001', 'com.gridsoft.encwebsvc.ws.dao.impl.CacheDao', 'getBadwords', '查询敏感词', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('78', 'App001', 'com.gridsoft.encwebsvc.ws.dao.impl.AppDao', 'getAppById', '根据application的id查询application', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('79', 'Utch014', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getDeptinfoByUid', '根据老师uid获取用户相关部门信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('80', 'Udep001', 'com.gridsoft.encwebsvc.ws.dao.impl.DepDao', 'getDepById', '根据部门id查询部门信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('81', 'Udep002', 'com.gridsoft.encwebsvc.ws.dao.impl.DepDao', 'getDepManagerByDepID', '根据部门id获取部门管理者', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('82', 'Uuse018', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getAllIdentity', '获取所有身份', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('83', 'Uuse019', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getIdentityType', '不定项查询用户身份类型(isuid--1:根据username 查询2:email 3:uid)', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('84', 'Note001', 'com.gridsoft.encwebsvc.ws.dao.impl.NoteDao', 'getNoteInfoById', '通过noteid查询note信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('85', 'Uuse020', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getParentByNameAndChildXh', '通过家长姓名和学生学号查询家长信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('86', 'Ustu015', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentInfoByUid', '通过用户uid查询学生信息、学生班级学校', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('87', 'Uuse021', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getRoleByIsReg', '根据是否注册查询角色', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('88', 'Usch028', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSchoolinfoByUid', '根据用户uid获取学校信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('89', 'Ustu017', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getBirthdayStudentByClassId', '根据班级id获取本月过生日的人', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('90', 'Tag001', 'com.gridsoft.encwebsvc.ws.dao.impl.TagDao', 'getTagByName', '通过标签名获取标签信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('91', 'Utch015', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getClassTeacherByClassid', '根据班级id获取班级班主任身份id', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('92', 'Utch016', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getAllTeacherByClassId', '根据班级id获取所有教师', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('93', 'Usch029', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAllSubject', '查询所有的科目信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('94', 'Usch030', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getTeacherGroupByTeacherId', '根据教师uid查询该教师所在教研组信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('95', 'Uuse022', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getStudentOrTeacherBySfzh', '通过身份证得到学生或老师的基本信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('96', 'Utch027', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getCourseByTeacherUid', '根据老师的uid得到他在course表中的任课信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('97', 'Ustu018', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getCourseByUid', '根据用户的uid得到他本人的选课信息列表', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('98', 'Uuse023', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getAllSubject', '得到全部的科目信息列表', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('99', 'Ustu019', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentInfoByIdentityid', '根据学生identityid获取学生及院系专业信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('100', 'Ustu020', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getMyClassmateByUid', '根据我的uid获取我的同班同学', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('101', 'Ustu021', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getMyAcademymateByUid', '根据我的uid获取我的同系同学', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('102', 'Ustu022', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getMyTeacherByUid', '获取我的教课老师信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('103', 'Utch028', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getSameDeptTeacherWithMe', '根据uid获取同部门的老师', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('104', 'Utch029', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getStudentInMyClass', '获取我教的课的学生', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('105', 'Utch030', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getSameSubjectTeacher', '获取和我教同科目的老师', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('106', 'Utch031', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherByKeyword', '根据姓名关键字模糊查询老师', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('107', 'Ustu023', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentClassTogether', '获取一起上课的同学', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('108', 'Ustu024', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentByKeyword', '根据姓名关键字模糊查询学生', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('109', 'Ustu025', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentCourse', '根据member表里面的uid查询学生的课程表信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('110', 'Utch032', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherCourse', '根据member表里面的查询老师的授课信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('111', 'Uuse024', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getEmptyClassroom', '根据主楼号，日期，节次查找空的自习室', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('112', 'Udep005', 'com.gridsoft.encwebsvc.ws.dao.impl.DepDao', 'getDeptBySchoolId', '根据学校查所有部门', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('113', 'Utch033', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getSchoolZZJG', '根据学校获取学校部门和部门下的老师', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('114', 'Uuse025', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getStudyClassroom', '根据老师的课程安排查找自习室', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('115', 'Ustu026', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentCourseByXh', '根据member表里面的uid获取学生的选课信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('116', 'Uuse026', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getAllJxlhAndJxlm', '获取所有的教学楼号教学楼名', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('117', 'Utch034', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherCourseByUid', '根据member表中的uid查询老师的课程表', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('118', 'Utch035', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherCourseByKeyWord', '根据老师名字、课程名中关键字查询老师课程', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('119', 'Utch036', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherCourseByJshOrKch', '根据老师号或课程号查询老师课程', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('120', 'Utch037', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTotalPageByJshOrKch', '根据老师号或课程号查询老师课程总页数', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('121', 'Usch031', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAcademyByUid', '根据学校id查询所有的院系信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('122', 'Usch032', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getSpecialtyByUid', '根据学校id查询所有的专业信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('123', 'Usch033', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAllStudentByYxid', '根据学校id查询所有的学生信息(注册、非注册)', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('124', 'Usch034', 'com.gridsoft.encwebsvc.ws.dao.impl.SchoolDao', 'getAllClassByYxid', '根据院系id查询该学院下所有班级信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('125', 'Ustu027', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getStudentInfoByOrg', '根据学校ID或院系ID或专业ID或班级ID或年级查询的学生信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('126', 'Ustu028', 'com.gridsoft.encwebsvc.ws.dao.impl.StudentDao', 'getHaveCourseUids', '根据uid列表，筛选有课的uid相关信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('127', 'Uuse033', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'updatePositionConfig', '更新定位配置参数', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('128', 'Utch040', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getSameDeptTeacherIncludeMe', '根据我的uid获取和我同部门的老师（包括自己）', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('129', 'Uuse027', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getPositionInfo', '获取本地position信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('130', 'Uuse028', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserByUserName', '根据用户名模糊查询', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('131', 'Uuse029', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getUserByUserNameANDParam', '特定集合模糊查询用户', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('132', 'Uuse030', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getSomeTimePositionInfo', '获取用户一段时间内的定位信息', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('133', 'Uuse031', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getAllPositionToDB', '定时同步定位信息数据库', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('134', 'Uuse032', 'com.gridsoft.encwebsvc.ws.dao.impl.UserDao', 'getPositionConfig', '获取定位配置参数', '00', null, null, null, null, null);
INSERT INTO `uc_service` VALUES ('135', 'Utch041', 'com.gridsoft.encwebsvc.ws.dao.impl.TeacherDao', 'getTeacherHaveCourseUids', '根据uid列表，筛选有课的uid相关信息(老师)', '00', null, null, null, null, null);

-- ----------------------------
-- Table structure for uc_serviceio
-- ----------------------------
DROP TABLE IF EXISTS `uc_serviceio`;
CREATE TABLE `uc_serviceio` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `svrID` int(11) NOT NULL COMMENT '服务id.对应uc_service表中的id',
  `ioName` varchar(40) NOT NULL COMMENT '输入输出名.',
  `ioType` char(1) NOT NULL COMMENT '输入输出标识.I:输入参数;O:输出参数',
  `ioDes` varchar(100) DEFAULT NULL COMMENT '输入输出说明.',
  `isList` char(1) NOT NULL COMMENT '是否是列表.0:非列表;1:列表;默认0',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n00.有效 99.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统一身份认证服务输入输出组表, 该表定义统一身份认证系统对外提供的webService服务输入输出组表';

-- ----------------------------
-- Records of uc_serviceio
-- ----------------------------

-- ----------------------------
-- Table structure for uc_servicelog
-- ----------------------------
DROP TABLE IF EXISTS `uc_servicelog`;
CREATE TABLE `uc_servicelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(200) NOT NULL,
  `message` varchar(400) NOT NULL,
  `excutetime` double(11,4) DEFAULT NULL,
  `credate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_servicelog
-- ----------------------------

-- ----------------------------
-- Table structure for uc_service_param
-- ----------------------------
DROP TABLE IF EXISTS `uc_service_param`;
CREATE TABLE `uc_service_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fwid` int(11) NOT NULL,
  `csmc` varchar(200) DEFAULT NULL,
  `csms` varchar(200) DEFAULT NULL,
  `cslx` int(2) DEFAULT NULL,
  `param_type` varchar(100) DEFAULT NULL,
  `sfbt` int(2) DEFAULT NULL,
  `param_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_service_param
-- ----------------------------

-- ----------------------------
-- Table structure for uc_settings
-- ----------------------------
DROP TABLE IF EXISTS `uc_settings`;
CREATE TABLE `uc_settings` (
  `k` varchar(32) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_settings
-- ----------------------------
INSERT INTO `uc_settings` VALUES ('accessemail', '');
INSERT INTO `uc_settings` VALUES ('censoremail', '');
INSERT INTO `uc_settings` VALUES ('censorusername', '');
INSERT INTO `uc_settings` VALUES ('dateformat', 'y-n-j');
INSERT INTO `uc_settings` VALUES ('doublee', '0');
INSERT INTO `uc_settings` VALUES ('nextnotetime', '0');
INSERT INTO `uc_settings` VALUES ('timeoffset', '28800');
INSERT INTO `uc_settings` VALUES ('privatepmthreadlimit', '25');
INSERT INTO `uc_settings` VALUES ('chatpmthreadlimit', '30');
INSERT INTO `uc_settings` VALUES ('chatpmmemberlimit', '35');
INSERT INTO `uc_settings` VALUES ('pmfloodctrl', '15');
INSERT INTO `uc_settings` VALUES ('pmcenter', '0');
INSERT INTO `uc_settings` VALUES ('sendpmseccode', '1');
INSERT INTO `uc_settings` VALUES ('pmsendregdays', '0');
INSERT INTO `uc_settings` VALUES ('maildefault', 'username@21cn.com');
INSERT INTO `uc_settings` VALUES ('mailsend', '1');
INSERT INTO `uc_settings` VALUES ('mailserver', 'smtp.21cn.com');
INSERT INTO `uc_settings` VALUES ('mailport', '25');
INSERT INTO `uc_settings` VALUES ('mailauth', '1');
INSERT INTO `uc_settings` VALUES ('mailfrom', 'UCenter <username@21cn.com>');
INSERT INTO `uc_settings` VALUES ('mailauth_username', 'username@21cn.com');
INSERT INTO `uc_settings` VALUES ('mailauth_password', 'password');
INSERT INTO `uc_settings` VALUES ('maildelimiter', '0');
INSERT INTO `uc_settings` VALUES ('mailusername', '1');
INSERT INTO `uc_settings` VALUES ('mailsilent', '1');
INSERT INTO `uc_settings` VALUES ('version', '1.6.0');
INSERT INTO `uc_settings` VALUES ('timeformat', 'h:i A');

-- ----------------------------
-- Table structure for uc_specialtyinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_specialtyinfo`;
CREATE TABLE `uc_specialtyinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.专业id',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.专业所属学校id',
  `yxid` int(11) DEFAULT NULL COMMENT '院系id.专业所属院系号',
  `yxbm` varchar(20) DEFAULT NULL COMMENT '院系编码.该处为冗余设计，为导入数据，同步等使用',
  `zyh` varchar(20) DEFAULT NULL COMMENT '专业号.学校自编',
  `zymc` varchar(40) DEFAULT NULL COMMENT '专业名称.',
  `zyjc` varchar(20) DEFAULT NULL COMMENT '专业简称.',
  `xjzyh` varchar(20) DEFAULT NULL COMMENT '校级专业号.学校自编专业号',
  `xjzym` varchar(40) DEFAULT NULL COMMENT '校级专业名.学校自编专业名',
  `zyywmc` varchar(180) DEFAULT NULL COMMENT '专业英文名称.',
  `xz` varchar(10) DEFAULT NULL COMMENT '学制.',
  `xkmlm` char(2) DEFAULT NULL COMMENT '学科门类码.学位授予和人才培\r\n养学科目录\r\n专业学位授予和人\r\n才培养目录\r\n普通高等学校本科\r\n专业目录\r\n普通高等学校高职\r\n高专教育指导性专\r\n业目录（试行）',
  `zyccm` char(2) DEFAULT NULL COMMENT '专业层次码.01本科，02研究生，03专科',
  `jlny` varchar(10) DEFAULT NULL COMMENT '建立年月.',
  `zyjj` varchar(200) DEFAULT NULL COMMENT '专业简介.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专业信息表, 该表定义高校专业信息';

-- ----------------------------
-- Records of uc_specialtyinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_sqlcache
-- ----------------------------
DROP TABLE IF EXISTS `uc_sqlcache`;
CREATE TABLE `uc_sqlcache` (
  `sqlid` char(6) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL,
  `expiry` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sqlid`),
  KEY `expiry` (`expiry`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_sqlcache
-- ----------------------------

-- ----------------------------
-- Table structure for uc_studentcourse
-- ----------------------------
DROP TABLE IF EXISTS `uc_studentcourse`;
CREATE TABLE `uc_studentcourse` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `xh` varchar(12) DEFAULT NULL COMMENT '学号.与xjb里面的学号对应',
  `kch` varchar(10) DEFAULT NULL COMMENT '课程号.与code_kcbj里面的课程号对应',
  `kxh` int(4) DEFAULT '0' COMMENT '课序号.与pkjg表里面的kxh对应',
  `kcm` varchar(50) DEFAULT NULL COMMENT '课程名.',
  `xnd` varchar(9) DEFAULT NULL COMMENT '学年度.学年度字段',
  `kkxq` varchar(2) DEFAULT NULL COMMENT '课程学期.',
  `kclb` varchar(10) DEFAULT NULL COMMENT '课程类别.',
  `kcsx` varchar(4) DEFAULT NULL COMMENT '课程选修.',
  `zxs` decimal(4,0) DEFAULT NULL COMMENT '总学时.',
  `kslx` varchar(4) DEFAULT NULL COMMENT '考试类型.',
  `kcxz` varchar(20) DEFAULT NULL COMMENT '课程性质.',
  `skxq` int(2) DEFAULT '0' COMMENT '上课星期.',
  `skjc` int(2) DEFAULT '0' COMMENT '上课节次.',
  `skxs` int(2) DEFAULT '0' COMMENT '上课学时.',
  `jxlh` int(8) DEFAULT '0' COMMENT '教学楼号.',
  `jxlm` varchar(20) DEFAULT NULL COMMENT '教学楼名.',
  `jsm` varchar(20) DEFAULT NULL COMMENT '教室名.',
  `skzc` varchar(24) DEFAULT NULL COMMENT '上课周次.',
  `dsz` int(2) DEFAULT '0' COMMENT '单双周.',
  `xs1` int(2) DEFAULT '0' COMMENT '第1周.',
  `xs2` int(2) DEFAULT '0' COMMENT '第2周.',
  `xs3` int(2) DEFAULT '0' COMMENT '第3周.',
  `xs4` int(2) DEFAULT '0' COMMENT '第4周.',
  `xs5` int(2) DEFAULT '0' COMMENT '第5周.',
  `xs6` int(2) DEFAULT '0' COMMENT '第6周.',
  `xs7` int(2) DEFAULT '0' COMMENT '第7周.',
  `xs8` int(2) DEFAULT '0' COMMENT '第8周.',
  `xs9` int(2) DEFAULT '0' COMMENT '第9周.',
  `xs10` int(2) DEFAULT '0' COMMENT '第10周.',
  `xs11` int(2) DEFAULT '0' COMMENT '第11周.',
  `xs12` int(2) DEFAULT '0' COMMENT '第12周.',
  `xs13` int(2) DEFAULT '0' COMMENT '第13周.',
  `xs14` int(2) DEFAULT '0' COMMENT '第14周.',
  `xs15` int(2) DEFAULT '0' COMMENT '第15周.',
  `xs16` int(2) DEFAULT '0' COMMENT '第16周.',
  `xs17` int(2) DEFAULT '0' COMMENT '第17周.',
  `xs18` int(2) DEFAULT '0' COMMENT '第18周.',
  `xs19` int(2) DEFAULT '0' COMMENT '第19周.',
  `xs20` int(2) DEFAULT '0' COMMENT '第20周.',
  `xs21` int(2) DEFAULT '0' COMMENT '第21周.',
  `xs22` int(2) DEFAULT '0' COMMENT '第22周.',
  `xs23` int(2) DEFAULT '0' COMMENT '第23周.',
  `xs24` int(2) DEFAULT '0' COMMENT '第24周.',
  PRIMARY KEY (`id`),
  KEY `xkk` (`xh`,`kch`,`kxh`,`xnd`,`kkxq`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生选课信息表, 该表定义学生的选课信息';

-- ----------------------------
-- Records of uc_studentcourse
-- ----------------------------

-- ----------------------------
-- Table structure for uc_studentinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_studentinfo`;
CREATE TABLE `uc_studentinfo` (
  `identityid` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '身份ID.流水生成的主键',
  `schoolid` varchar(40) DEFAULT NULL COMMENT '学校ID.',
  `universalNo` varchar(40) DEFAULT NULL COMMENT '统一身份识别号.全校统一的身份识别号，可以各个系统识别。可作为一卡通号',
  `xh` varchar(20) DEFAULT NULL COMMENT '学号.学校自编',
  `xm` varchar(20) DEFAULT NULL COMMENT '姓名.',
  `xb` varchar(10) DEFAULT NULL COMMENT '性别.',
  `xbm` char(1) DEFAULT NULL COMMENT '性别码.',
  `sfzjh` varchar(20) DEFAULT NULL COMMENT '身份证件号.',
  `xslbm` char(2) DEFAULT NULL COMMENT '学生类别码.培养层次。33中职学生；34工读学生；41专科生；42本科生；43研究生；44学位学生',
  `nj` varchar(8) DEFAULT NULL COMMENT '年级.学生所在年级，如2012，表示2012级',
  `rxsj` varchar(8) DEFAULT NULL,
  `yxid` mediumint(8) DEFAULT NULL COMMENT '院系id.学生所在院系id',
  `zyid` mediumint(8) DEFAULT NULL COMMENT '专业id.学生所在专业id',
  `bjid` mediumint(8) DEFAULT NULL COMMENT '班级id.学生所在班级id',
  `yxbm` varchar(20) DEFAULT NULL COMMENT '院系编码.院系编码，由学校制定。该处为冗余设计，为导入数据，同步等使用',
  `zybm` varchar(20) DEFAULT NULL COMMENT '专业编码.专业编码，由学校制定。该处为冗余设计，为导入数据，同步等使用',
  `bjbm` varchar(20) DEFAULT NULL COMMENT '班级编码.班级编码，由学校制定。该处为冗余设计，为导入数据，同步等使用',
  `bm` varchar(20) DEFAULT NULL COMMENT '班名',
  `gkksh` varchar(20) DEFAULT NULL COMMENT '高考考生号.',
  `lqh` varchar(20) DEFAULT NULL COMMENT '录取号.',
  `pyccjd` varchar(20) DEFAULT NULL COMMENT '培养层次阶段.本科，专升本，高职等',
  `ywxm` varchar(20) DEFAULT NULL COMMENT '英文姓名.',
  `xmpy` varchar(40) DEFAULT NULL COMMENT '姓名拼音.',
  `cym` varchar(20) DEFAULT NULL COMMENT '曾用名.',
  `csrq` varchar(8) DEFAULT NULL COMMENT '出生日期.',
  `csdm` varchar(20) DEFAULT NULL COMMENT '出生地码.',
  `jg` varchar(40) DEFAULT NULL COMMENT '籍贯.',
  `mz` varchar(20) DEFAULT NULL COMMENT '民族.该处为冗余设计，为导入数据，同步等使用',
  `mzm` char(2) DEFAULT NULL COMMENT '民族码.',
  `gjdq` varchar(60) DEFAULT NULL COMMENT '国籍地区.该处为冗余设计，为导入数据，同步等使用',
  `gjdqm` varchar(40) DEFAULT NULL COMMENT '国籍地区码.',
  `sfzjlx` varchar(10) DEFAULT NULL COMMENT '身份证件类型.该处为冗余设计，为导入数据，同步等使用',
  `sfzjlxm` char(2) DEFAULT NULL COMMENT '身份证件类型码.',
  `hyzk` varchar(10) DEFAULT NULL COMMENT '婚姻状况.该处为冗余设计，为导入数据，同步等使用',
  `hyzkm` char(1) DEFAULT NULL COMMENT '婚姻状况码.',
  `gatqw` varchar(10) DEFAULT NULL COMMENT '港澳台侨外.该处为冗余设计，为导入数据，同步等使用',
  `gatqwm` char(2) DEFAULT NULL COMMENT '港澳台侨外码.',
  `zzmm` varchar(10) DEFAULT NULL COMMENT '政治面貌.该处为冗余设计，为导入数据，同步等使用',
  `zzmmm` char(1) DEFAULT NULL COMMENT '政治面貌码.',
  `jkzk` varchar(20) DEFAULT NULL COMMENT '健康状况.该处为冗余设计，为导入数据，同步等使用',
  `jkzkm` char(1) DEFAULT NULL COMMENT '健康状况码.',
  `xyzj` varchar(20) DEFAULT NULL COMMENT '信仰宗教.该处为冗余设计，为导入数据，同步等使用',
  `xyzjm` char(2) DEFAULT NULL COMMENT '信仰宗教码.',
  `xx` varchar(20) DEFAULT NULL COMMENT '血型.该处为冗余设计，为导入数据，同步等使用',
  `xxm` char(2) DEFAULT NULL COMMENT '血型码.',
  `zp` varchar(50) DEFAULT NULL COMMENT '照片.',
  `sfdszn` char(1) DEFAULT NULL COMMENT '是否独生子女.1 是独生子女0 不是独生子女',
  `xzz` varchar(100) DEFAULT NULL COMMENT '现住址.指本人的常住地址',
  `hkszd` varchar(100) DEFAULT NULL COMMENT '户口所在地.指户口所在地址，包括省（自治区、直辖市）/地（市、州）/县（区、旗）/乡（镇）/街（村）详细地址',
  `hkxzm` char(1) DEFAULT NULL COMMENT '户口性质码.指公安户籍部门确认的农业户口或非农业户口',
  `sfldrk` char(1) DEFAULT NULL COMMENT '是否流动人口.',
  `tc` varchar(100) DEFAULT NULL COMMENT '特长.指某一方面特殊的能力或技能',
  `lxdh` varchar(20) DEFAULT NULL COMMENT '联系电话.本人的联系电话号码',
  `txdz` varchar(100) DEFAULT NULL COMMENT '通信地址.',
  `yzbm` varchar(6) DEFAULT NULL COMMENT '邮政编码.通信地址的邮政编码',
  `jstxh` varchar(40) DEFAULT NULL COMMENT '即时通讯号.',
  `dzxx` varchar(40) DEFAULT NULL COMMENT '电子信箱.学生在互联网上的电子邮件信箱地址',
  `zydz` varchar(40) DEFAULT NULL COMMENT '主页地址.学生在互联网上的个人主页地址',
  `xjh` varchar(30) DEFAULT NULL COMMENT '学籍号.全国唯一的学籍号',
  `remark` varchar(50) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n00.有效 99.已删除',
  `xxbh` varchar(20) DEFAULT NULL,
  `xz` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`identityid`),
  UNIQUE KEY `universalNo` (`universalNo`) USING BTREE,
  KEY `xh` (`xh`) USING BTREE,
  KEY `bm` (`bm`) USING BTREE,
  KEY `bjbm` (`bjbm`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生信息表_高校, 该表定义（高校）学生的基本信息';

-- ----------------------------
-- Records of uc_studentinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_subject
-- ----------------------------
DROP TABLE IF EXISTS `uc_subject`;
CREATE TABLE `uc_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kch` varchar(20) NOT NULL,
  `kcm` varchar(100) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学院课程科目信息总表';

-- ----------------------------
-- Records of uc_subject
-- ----------------------------

-- ----------------------------
-- Table structure for uc_sysadmin
-- ----------------------------
DROP TABLE IF EXISTS `uc_sysadmin`;
CREATE TABLE `uc_sysadmin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id.',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名.',
  `password` varchar(100) DEFAULT NULL COMMENT '密码.存储md5值',
  `schoolid` int(11) DEFAULT NULL COMMENT '学校id.管理员所属学校id',
  `adminType` char(2) DEFAULT NULL COMMENT '管理员类型.00:超级管理员;01校级管理员,默认为01',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名.',
  `contact` varchar(60) DEFAULT NULL COMMENT '联系方式.',
  `remark` varchar(50) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n00.有效 99.已删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Ucenter管理员表, 该表定义Ucenter的管理员.该管理员可以脱离社区用户独立存在.其意义是,可以预先定义一些管';

-- ----------------------------
-- Records of uc_sysadmin
-- ----------------------------
INSERT INTO `uc_sysadmin` VALUES ('1', 'administrator', '96e79218965eb72c92a549dd5a330112', '-1', null, 'administrator', '', '', null, null, null, null);

-- ----------------------------
-- Table structure for uc_tags
-- ----------------------------
DROP TABLE IF EXISTS `uc_tags`;
CREATE TABLE `uc_tags` (
  `tagname` char(20) NOT NULL,
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `data` mediumtext,
  `expiration` int(10) unsigned NOT NULL,
  KEY `tagname` (`tagname`,`appid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_tags
-- ----------------------------

-- ----------------------------
-- Table structure for uc_teachbuliding
-- ----------------------------
DROP TABLE IF EXISTS `uc_teachbuliding`;
CREATE TABLE `uc_teachbuliding` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.教室id',
  `xxid` int(11) DEFAULT NULL COMMENT '学校id.教室所属学校id',
  `xqh` varchar(10) DEFAULT NULL COMMENT '校区号.',
  `jxlh` varchar(20) DEFAULT NULL COMMENT '教学楼号.学校自定义',
  `jxlmc` varchar(20) DEFAULT NULL COMMENT '教学楼名称.',
  `jxljj` varchar(200) DEFAULT NULL COMMENT '教学楼简介.',
  `jxltp` varchar(100) DEFAULT NULL COMMENT '教学楼图片.',
  `sortOrder` int(3) DEFAULT NULL COMMENT '排序号.',
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='教学楼信息表, 该表定义学校教学楼信息。';

-- ----------------------------
-- Records of uc_teachbuliding
-- ----------------------------

-- ----------------------------
-- Table structure for uc_teachercourse
-- ----------------------------
DROP TABLE IF EXISTS `uc_teachercourse`;
CREATE TABLE `uc_teachercourse` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `jsh1` varchar(12) DEFAULT NULL COMMENT '教师号1.',
  `jsm1` varchar(20) DEFAULT NULL COMMENT '教师名2.',
  `jsh2` varchar(12) DEFAULT NULL COMMENT '教师号2.',
  `jsm2` varchar(20) DEFAULT NULL COMMENT '教师名2.',
  `kch` varchar(10) DEFAULT NULL COMMENT '课程号.code_kcbj里面的kch',
  `kxh` int(4) DEFAULT '0' COMMENT '课序号.pkjg表里面的kxh',
  `kcm` varchar(50) DEFAULT NULL COMMENT '课程名.',
  `xnd` varchar(9) DEFAULT NULL COMMENT '学年度.学年度字段',
  `kkxq` varchar(2) DEFAULT NULL COMMENT '课程学期.',
  `kclb` varchar(10) DEFAULT NULL COMMENT '课程类别.',
  `kcsx` varchar(4) DEFAULT NULL COMMENT '课程选修.',
  `zxs` decimal(4,1) DEFAULT NULL COMMENT '总学时.',
  `kslx` varchar(4) DEFAULT NULL COMMENT '考试类型.',
  `kcxz` varchar(20) DEFAULT NULL COMMENT '课程性质.',
  `skxq` int(2) DEFAULT '0' COMMENT '上课星期.',
  `skjc` int(2) DEFAULT '0' COMMENT '上课节次.',
  `skxs` int(2) DEFAULT '0' COMMENT '上课学时.',
  `jxlh` int(8) DEFAULT '0' COMMENT '教学楼号.',
  `jxlm` varchar(20) DEFAULT NULL COMMENT '教学楼名.',
  `jsm` varchar(20) DEFAULT NULL COMMENT '教室名.',
  `skzc` varchar(24) DEFAULT NULL COMMENT '上课周次.',
  `dsz` int(2) DEFAULT '0' COMMENT '单双周.',
  `xs1` int(2) DEFAULT '0' COMMENT '第1周.',
  `xs2` int(2) DEFAULT '0' COMMENT '第2周.',
  `xs3` int(2) DEFAULT '0' COMMENT '第3周.',
  `xs4` int(2) DEFAULT '0' COMMENT '第4周.',
  `xs5` int(2) DEFAULT '0' COMMENT '第5周.',
  `xs6` int(2) DEFAULT '0' COMMENT '第6周.',
  `xs7` int(2) DEFAULT '0' COMMENT '第7周.',
  `xs8` int(2) DEFAULT '0' COMMENT '第8周.',
  `xs9` int(2) DEFAULT '0' COMMENT '第9周.',
  `xs10` int(2) DEFAULT '0' COMMENT '第10周.',
  `xs11` int(2) DEFAULT '0' COMMENT '第11周.',
  `xs12` int(2) DEFAULT '0' COMMENT '第12周.',
  `xs13` int(2) DEFAULT '0' COMMENT '第13周.',
  `xs14` int(2) DEFAULT '0' COMMENT '第14周.',
  `xs15` int(2) DEFAULT '0' COMMENT '第15周.',
  `xs16` int(2) DEFAULT '0' COMMENT '第16周.',
  `xs17` int(2) DEFAULT '0' COMMENT '第17周.',
  `xs18` int(2) DEFAULT '0' COMMENT '第18周.',
  `xs19` int(2) DEFAULT '0' COMMENT '第19周.',
  `xs20` int(2) DEFAULT '0' COMMENT '第20周.',
  `xs21` int(2) DEFAULT '0' COMMENT '第21周.',
  `xs22` int(2) DEFAULT '0' COMMENT '第22周.',
  `xs23` int(2) DEFAULT '0' COMMENT '第23周.',
  `xs24` int(2) DEFAULT '0' COMMENT '第24周.',
  `xf` decimal(4,1) DEFAULT NULL,
  `zxf` decimal(4,1) DEFAULT NULL,
  `code_kcm` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jjkkxk` (`jsh1`,`jsm1`,`jsh2`,`jsm2`,`kch`,`kxh`,`xnd`,`kkxq`) USING BTREE,
  KEY `jxlh` (`skjc`,`skxs`,`jxlh`,`jsm`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='教师授课信息表, 该表定义老师的授课信息';

-- ----------------------------
-- Records of uc_teachercourse
-- ----------------------------

-- ----------------------------
-- Table structure for uc_teacherinfo
-- ----------------------------
DROP TABLE IF EXISTS `uc_teacherinfo`;
CREATE TABLE `uc_teacherinfo` (
  `identityid` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '身份ID.',
  `schoolid` varchar(40) DEFAULT NULL COMMENT '学校ID.',
  `universalNo` varchar(40) DEFAULT NULL COMMENT '统一身份识别号.全校统一的身份识别号，可以各个系统识别。可作为一卡通号',
  `zgh` varchar(40) DEFAULT NULL COMMENT '职工号.职工号',
  `jsh` varchar(40) DEFAULT NULL COMMENT '教师号.教师号',
  `xm` varchar(20) DEFAULT NULL COMMENT '姓名.',
  `sfzjh` varchar(20) DEFAULT NULL COMMENT '身份证件号.',
  `depid` mediumint(8) DEFAULT NULL COMMENT '部门id.老师所在部门id',
  `bmbm` varchar(20) DEFAULT NULL COMMENT '部门编码.该处为冗余设计，为导入数据，同步等使用',
  `bmmc` varchar(60) DEFAULT NULL COMMENT '部门名称.该处为冗余设计，为导入数据，同步等使用',
  `ywxm` varchar(20) DEFAULT NULL COMMENT '英文姓名.',
  `xmpy` varchar(40) DEFAULT NULL COMMENT '姓名拼音.',
  `cym` varchar(20) DEFAULT NULL COMMENT '曾用名.',
  `xb` varchar(10) DEFAULT NULL COMMENT '性别.',
  `xbm` char(1) DEFAULT NULL COMMENT '性别码.',
  `csrq` varchar(10) DEFAULT NULL COMMENT '出生日期.',
  `csd` varchar(40) DEFAULT NULL COMMENT '出生地.',
  `csdm` varchar(20) DEFAULT NULL COMMENT '出生地码.',
  `jg` varchar(40) DEFAULT NULL COMMENT '籍贯.',
  `mz` varchar(20) DEFAULT NULL COMMENT '民族.该处为冗余设计，为导入数据，同步等使用',
  `mzm` char(2) DEFAULT NULL COMMENT '民族码.',
  `gjdq` varchar(60) DEFAULT NULL COMMENT '国籍地区.该处为冗余设计，为导入数据，同步等使用',
  `gjdqm` varchar(40) DEFAULT NULL COMMENT '国籍地区码.',
  `sfzjlx` varchar(10) DEFAULT NULL COMMENT '身份证件类型.该处为冗余设计，为导入数据，同步等使用',
  `sfzjlxm` char(2) DEFAULT NULL COMMENT '身份证件类型码.',
  `hyzk` varchar(10) DEFAULT NULL COMMENT '婚姻状况.该处为冗余设计，为导入数据，同步等使用',
  `hyzkm` char(1) DEFAULT NULL COMMENT '婚姻状况码.',
  `gatqw` varchar(10) DEFAULT NULL COMMENT '港澳台侨外.该处为冗余设计，为导入数据，同步等使用',
  `gatqwm` char(2) DEFAULT NULL COMMENT '港澳台侨外码.',
  `zzmm` varchar(10) DEFAULT NULL COMMENT '政治面貌.该处为冗余设计，为导入数据，同步等使用',
  `zzmmm` char(1) DEFAULT NULL COMMENT '政治面貌码.',
  `jkzk` varchar(20) DEFAULT NULL COMMENT '健康状况.该处为冗余设计，为导入数据，同步等使用',
  `jkzkm` char(1) DEFAULT NULL COMMENT '健康状况码.',
  `xyzj` varchar(20) DEFAULT NULL COMMENT '信仰宗教.该处为冗余设计，为导入数据，同步等使用',
  `xyzjm` char(2) DEFAULT NULL COMMENT '信仰宗教码.',
  `xx` varchar(20) DEFAULT NULL COMMENT '血型.该处为冗余设计，为导入数据，同步等使用',
  `xxm` char(2) DEFAULT NULL COMMENT '血型码.',
  `zp` varchar(50) DEFAULT NULL COMMENT '照片.',
  `sfzjyxq` varchar(8) DEFAULT NULL COMMENT '身份证件有效期.',
  `xqh` varchar(10) DEFAULT NULL COMMENT '校区号.',
  `zgxl` varchar(20) DEFAULT NULL COMMENT '最高学历.指本人接受由国家教育行政部门认可的各类学校正式教育并获得有关证书的最高学历。',
  `zgxlm` char(2) DEFAULT NULL COMMENT '最高学历码.指本人接受由国家教育行政部门认可的各类学校正式教育并获得有关证书的最高学历。',
  `zgxw` varchar(20) DEFAULT NULL COMMENT '最高学位.',
  `zgxwm` char(2) DEFAULT NULL COMMENT '最高学位码.',
  `whcd` varchar(20) DEFAULT NULL COMMENT '文化程度.指本人实际具有的文化水平',
  `whcdm` char(2) DEFAULT NULL COMMENT '文化程度码.指本人实际具有的文化水平',
  `byxx` varchar(40) DEFAULT NULL COMMENT '毕业学校.',
  `byrq` varchar(10) DEFAULT NULL COMMENT '毕业日期.',
  `cjgzsj` varchar(10) DEFAULT NULL COMMENT '参加工作时间.',
  `lxrq` varchar(10) DEFAULT NULL COMMENT '来校日期.来校工作的实际报到日期，以人事部门记载为准',
  `qxrq` varchar(10) DEFAULT NULL COMMENT '起薪日期.人事部门正式开始计算薪水的日期',
  `cjny` varchar(10) DEFAULT NULL COMMENT '从教年月.指本人开始从事教育工作的年月',
  `bzlb` varchar(20) DEFAULT NULL COMMENT '编制类别.该处为冗余设计，为导入数据，同步等使用',
  `bzlbm` char(2) DEFAULT NULL COMMENT '编制类别码.',
  `jzglb` varchar(20) DEFAULT NULL COMMENT '教职工类别.该处为冗余设计，为导入数据，同步等使用',
  `jzglbm` char(2) DEFAULT NULL COMMENT '教职工类别码.',
  `rkzk` varchar(20) DEFAULT NULL COMMENT '任课状况.该处为冗余设计，为导入数据，同步等使用',
  `rkzkm` char(2) DEFAULT NULL COMMENT '任课状况码.',
  `pyxz` varchar(20) DEFAULT NULL COMMENT '聘用性质.聘用性质',
  `pyxzm` char(2) DEFAULT NULL COMMENT '聘用性质码.聘用性质码',
  `dabh` varchar(60) DEFAULT NULL COMMENT '档案编号.存档部门为本人档案确定的管理编号',
  `dqzt` varchar(20) DEFAULT NULL COMMENT '当前状态.',
  `dqztm` char(2) DEFAULT NULL COMMENT '当前状态码.两位代码的第一位0 表示不在职，1 表示在职',
  `tc` varchar(60) DEFAULT NULL COMMENT '特长.指本人在某一方面的特殊能力或技能',
  `xklbm` varchar(20) DEFAULT NULL COMMENT '学科类别码.学位授予和人才培养学科目录',
  `yjxkm` char(2) DEFAULT NULL COMMENT '一级学科码.学位授予和人才培养学科目录',
  `ejxkm` varchar(20) DEFAULT NULL COMMENT '二级学科码.学位授予和人才培养学科目录',
  `yjfx` varchar(40) DEFAULT NULL COMMENT '研究方向.从事研究方向的名称，或专业方向',
  `gwlbm` char(2) DEFAULT NULL COMMENT '岗位类别码.如：1 基础课；2专业课；3实践技术指导；9其他',
  `sfjzjs` char(1) DEFAULT NULL COMMENT '是否兼职教\r\n师.',
  `sfssxjs` char(1) DEFAULT NULL COMMENT '是否双师型\r\n教师\r\n.',
  `zyjszg` varchar(20) DEFAULT NULL COMMENT '专业技术资格.',
  `zyjszwjb` varchar(20) DEFAULT NULL COMMENT '专业技术职务级别.该处为冗余设计，为导入数据，同步等使用',
  `zyjszwjm` char(4) DEFAULT NULL COMMENT '专业技术职务级别码.',
  `zw` varchar(20) DEFAULT NULL COMMENT '职务.',
  `zj` varchar(20) DEFAULT NULL COMMENT '职级.',
  `zc` varchar(20) DEFAULT NULL COMMENT '职称.',
  `zcjb` varchar(20) DEFAULT NULL COMMENT '职称级别.',
  `grjszwmc` varchar(20) DEFAULT NULL COMMENT '工人技术职务名称.',
  `lxdh` varchar(20) DEFAULT NULL,
  `remark` varchar(20) DEFAULT NULL COMMENT '注释说明.注释说明',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`identityid`),
  UNIQUE KEY `universalNo` (`universalNo`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='教职工信息表, 该表定义(高校)教职工的基本信息';

-- ----------------------------
-- Records of uc_teacherinfo
-- ----------------------------

-- ----------------------------
-- Table structure for uc_teacher_subject
-- ----------------------------
DROP TABLE IF EXISTS `uc_teacher_subject`;
CREATE TABLE `uc_teacher_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jsh` varchar(20) NOT NULL,
  `identityid` int(11) NOT NULL,
  `subid` int(11) NOT NULL,
  `kch` varchar(20) NOT NULL,
  `kkxnd` varchar(20) NOT NULL,
  `kczxs` int(11) NOT NULL,
  `kkxq` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_teacher_subject
-- ----------------------------

-- ----------------------------
-- Table structure for uc_uia_func
-- ----------------------------
DROP TABLE IF EXISTS `uc_uia_func`;
CREATE TABLE `uc_uia_func` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id.',
  `appName` varchar(20) NOT NULL COMMENT '应用名称.一般定义为英文',
  `appAlias` varchar(20) DEFAULT NULL COMMENT '应用别名.一般为中文别名',
  `appDesc` varchar(40) DEFAULT NULL COMMENT '应用描述.',
  `appVersion` varchar(20) DEFAULT NULL COMMENT '应用版本.',
  `appEntry` varchar(20) DEFAULT NULL COMMENT '应用入口标识.程序中使用的应用入口',
  `pid` int(11) DEFAULT NULL COMMENT '父级id.该应用的父应用id',
  `remark` varchar(40) DEFAULT NULL COMMENT '注释说明.',
  `input_userid` varchar(20) DEFAULT NULL COMMENT '录入人.取值为登录用户ID',
  `update_userid` varchar(20) DEFAULT NULL COMMENT '修改人.取值为登录用户ID',
  `lastupdate` int(11) DEFAULT NULL COMMENT '最近更新时间.最近更新时间,该记录最后一次被修改的时间.如果是新建记录,则是插入的时间',
  `rec_flag` char(2) DEFAULT NULL COMMENT '记录标志.(预留字段)用于记录该条记录当前的状态,可能的取值为\r\n1.有效 2.已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='UCenter应用功能点表, 该表定义Ucenter应用功能点信息';

-- ----------------------------
-- Records of uc_uia_func
-- ----------------------------
INSERT INTO `uc_uia_func` VALUES ('1', 'schmanager', '学校设置', null, null, 'schmanager', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('2', 'peoplemanager', '人员管理', null, null, 'peoplemanager', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('3', 'esnmanager', '社区管理', null, null, 'esnmanager', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('4', 'ucmanager', '认证中心管理', null, null, 'ucmanager', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('5', 'system', '系统管理', null, null, 'system', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('6', 'logout', '退出系统', null, null, 'logout', '0', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('8', 'school', '学校管理', '', '', 'school', '1', '', null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('9', 'dept', '部门管理', '', '', 'dept', '1', '', null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('12', 'college', '院系管理', null, null, 'college', '1', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('11', 'class', '班级管理', '', '', 'class', '1', '', null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('13', 'specialty', '专业管理', null, null, 'specialty', '1', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('14', 'teachbuliding', '教学楼管理', null, null, 'teachbuliding', '1', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('15', 'classroom', '教室管理', null, null, 'classroom', '1', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('16', 'teacher', '老师管理', null, null, 'teacher', '2', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('17', 'student', '学生管理', null, null, 'student', '2', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('18', 'user', '社区用户管理', null, null, 'user', '3', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('19', 'application', '社区应用管理', null, null, 'application', '3', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('20', 'identity', '社区身份管理', null, null, 'identity', '3', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('21', 'role', '社区角色管理', null, null, 'role', '3', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('22', 'admins', '认证中心管理员管理', null, null, 'admins', '4', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('23', 'module', '认证中心模块管理', null, null, 'module', '4', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('24', 'ucrole', '认证中心角色管理', null, null, 'ucrole', '4', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('25', 'character', '数据字典管理', null, null, 'character', '5', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('26', 'service', '服务管理', null, null, 'service', '5', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('27', 'db', '数据备份', null, null, 'db', '5', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('28', 'cache', '更新缓存', null, null, 'cache', '5', null, null, null, null, null);
INSERT INTO `uc_uia_func` VALUES ('30', 'esnadmin', '社区用户管理员管理', '', '', 'esnadmin', '3', '', null, null, null, null);

-- ----------------------------
-- Table structure for uc_uia_role
-- ----------------------------
DROP TABLE IF EXISTS `uc_uia_role`;
CREATE TABLE `uc_uia_role` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID.Ucenter管理员角色id',
  `roleName` char(50) DEFAULT NULL COMMENT '角色名.UCenter管理员角色名',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`roleID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='UCenter角色表, 该表定义Ucenter角色。';

-- ----------------------------
-- Records of uc_uia_role
-- ----------------------------
INSERT INTO `uc_uia_role` VALUES ('1', '超级管理员', null);
INSERT INTO `uc_uia_role` VALUES ('2', '校级管理员', null);

-- ----------------------------
-- Table structure for uc_uia_r_role_func
-- ----------------------------
DROP TABLE IF EXISTS `uc_uia_r_role_func`;
CREATE TABLE `uc_uia_r_role_func` (
  `roleID` int(11) NOT NULL COMMENT '角色ID.角色id，对应uc_uia_role 表中的id',
  `appID` int(11) NOT NULL DEFAULT '0' COMMENT '应用ID.应用id，对应uc_uia_func表中的id',
  `roleExtend` varchar(255) DEFAULT NULL COMMENT '权限控制扩展.用于对角色权限的进一步控制。如可设定为A,D,U已表示该角色对该功能点有添加,删除,更新的权限;通过该属性与展示层代码的配合，完成权限的详细控制',
  `sortorder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`roleID`,`appID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter角色功能点对应表, 该表定义角色与应用的对应关系。一个角色可以对应多个功能点（应用）。';

-- ----------------------------
-- Records of uc_uia_r_role_func
-- ----------------------------
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '11', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '9', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '8', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '12', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '13', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '14', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '15', 'I,D,U,S,C', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '16', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '17', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '18', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '19', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '20', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '21', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '22', 'I,D,U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '23', 'U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '24', 'U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '25', 'U,S', null, null);
INSERT INTO `uc_uia_r_role_func` VALUES ('1', '26', 'U,S', null, null);

-- ----------------------------
-- Table structure for uc_uia_r_user_role
-- ----------------------------
DROP TABLE IF EXISTS `uc_uia_r_user_role`;
CREATE TABLE `uc_uia_r_user_role` (
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户id.用户id，对应uc_members表中的uid',
  `roleID` mediumint(8) NOT NULL DEFAULT '0' COMMENT '角色ID.角色id，对应uc_role 表中的RoleID',
  `sortOrder` int(11) DEFAULT NULL COMMENT '顺序号.排列顺序时的序号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注.备注',
  PRIMARY KEY (`uid`,`roleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter用户角色对应表, 该表定义用户与角色的对应关系。一个用户可以对应多个角色。';

-- ----------------------------
-- Records of uc_uia_r_user_role
-- ----------------------------
INSERT INTO `uc_uia_r_user_role` VALUES ('1', '1', null, null);

-- ----------------------------
-- Table structure for uc_vars
-- ----------------------------
DROP TABLE IF EXISTS `uc_vars`;
CREATE TABLE `uc_vars` (
  `name` char(32) NOT NULL DEFAULT '',
  `value` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_vars
-- ----------------------------

-- ----------------------------
-- View structure for uc_v_service_log
-- ----------------------------
DROP VIEW IF EXISTS `uc_v_service_log`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `uc_v_service_log` AS select `log`.`id` AS `id`,`log`.`service` AS `service`,`log`.`message` AS `message`,`log`.`excutetime` AS `excutetime`,`log`.`credate` AS `date`,`service`.`class` AS `class`,`service`.`method` AS `method`,`service`.`serviceDes` AS `serviceDes` from (`uc_servicelog` `log` left join `uc_service` `service` on((`service`.`serviceID` = `log`.`service`))) order by `log`.`credate` desc ;

-- ----------------------------
-- View structure for uc_v_stuinfo
-- ----------------------------
DROP VIEW IF EXISTS `uc_v_stuinfo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `uc_v_stuinfo` AS select `stu`.`identityid` AS `identityid`,`stu`.`xh` AS `xh`,`stu`.`xm` AS `xm`,`stu`.`pyccjd` AS `pyccjd`,`stu`.`xb` AS `xb`,`stu`.`sfzjh` AS `sfzjh`,`stu`.`nj` AS `nj`,`stu`.`lxdh` AS `lxdh`,`stu`.`schoolid` AS `schoolid`,`stu`.`yxid` AS `yxid`,`stu`.`zyid` AS `zyid`,`stu`.`bjid` AS `bjid`,`aca`.`yxmc` AS `yxmc`,`spe`.`zymc` AS `zymc`,`cla`.`bm` AS `bm` from (((`uc_studentinfo` `stu` left join `uc_academyinfo` `aca` on((`stu`.`yxid` = `aca`.`id`))) left join `uc_specialtyinfo` `spe` on((`stu`.`zyid` = `spe`.`id`))) left join `uc_classinfo` `cla` on((`stu`.`bjid` = `cla`.`id`))) ;
