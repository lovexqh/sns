-- 社团表
DROP TABLE IF EXISTS `ts_club`;
CREATE TABLE `ts_club` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `title` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `logo` varchar(255) default NULL,
  `type` int(1) default NULL,
  `banner` int(2) default NULL,
  `membercount` int(11) NOT NULL default 0,
  `balance` decimal(10,2) NOT NULL default 0.00,
  `ctime` int(11) default NULL,
  `pubtopic` int(1) NOT NULL default 0,
  `updoc` int(1) NOT NULL default 0,
  `downdoc` int(1) NOT NULL default 0,
  `isdel` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 社团财务表
DROP TABLE IF EXISTS `ts_club_account`;
CREATE TABLE `ts_club_account` (
  `id` int(11) NOT NULL auto_increment,
  `clubid` int(11) default NULL,
  `title` varchar(255) default NULL,
  `type` int(1) default NULL,
  `totalmoney` decimal(10,2) NOT NULL default 0.00,
  `addtime` int(11) default NULL,
  `adduid` int(11) default NULL,
  `updateuid` int(11) default NULL,
  `isdel` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 社团财务明细表
DROP TABLE IF EXISTS `ts_club_account_item`;
CREATE TABLE `ts_club_account_item` (
  `id` int(11) NOT NULL auto_increment,
  `clubid` int(11) default NULL,
  `accountid` int(11) default NULL,
  `title` varchar(255) default NULL,
  `money` decimal(10,2) NOT NULL default 0.00,
  `remark` varchar(255) default NULL,
  `useperson` varchar(60) default NULL,
  `addtime` int(11) default NULL,
  `adduid` int(11) default NULL,
  `deluid` int(11) default NULL,
  `isdel` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 社团附件表
DROP TABLE IF EXISTS `ts_club_document`;
CREATE TABLE `ts_club_document` (
  `id` int(11) NOT NULL auto_increment,
  `clubid` int(11) default NULL,
  `uid` int(11) default NULL,
  `title` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `filesize` int(10) NOT NULL default 0,
  `filetype` varchar(10) default NULL,
  `savepath` varchar(255) default NULL,
  `savename` varchar(255) default NULL,
  `downcount` int(11) NOT NULL default 0,
  `ctime` int(11) default NULL,
  `isdel` int(1) NOT NULL default 0,
  `isopen` tinyint (1) not null default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 社团部门表
DROP TABLE IF EXISTS `ts_club_dept`;
CREATE TABLE `ts_club_dept` (
  `id` int(11) NOT NULL auto_increment,
  `clubid` int(11) default NULL,
  `pid` int(11) default 0,
  `deptname` varchar(255) default NULL,
  `inputuid` int(11) default NULL,
  `isdel` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 社团成员表
DROP TABLE IF EXISTS `ts_club_member`;
CREATE TABLE `ts_club_member` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键id.',
  `clubid` int(11) default NULL COMMENT '社团id.',
  `uid` int(11) default NULL COMMENT '成员id.',
  `username` varchar(255) default NULL COMMENT '成员名.',
  `reason` varchar(255) default NULL COMMENT '原因.',
  `grade` int(4) default NULL COMMENT '年级.',
  `type` int(1) default NULL COMMENT '成员类型.1创建者，2管理员，3普通成员，4换届退出，5被踢出，6审核拒绝，0未审核',
  `titleid` int(11) default 0 COMMENT  '成员头衔.社团成员自己定义',
  `deptid` int(11) default 0 COMMENT '所在部门id.',
  `ctime` int(11) default NULL COMMENT '创建时间.',
  `mtime` int(11) default NULL COMMENT '处理时间.',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='社团成员表, 该表定义社团成员的信息';

-- 社团头衔表
DROP TABLE IF EXISTS `ts_club_title`;
CREATE TABLE `ts_club_title` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键id.',
  `clubid` int(11) default NULL COMMENT '社团id.',
  `title` varchar(30) default NULL COMMENT '头衔名称.',
  `ctime` int(11) default NULL COMMENT '创建时间.',
  `isdel` int(1) default '0' COMMENT '是否删除.0未删除，1已删除',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社团头衔表, 该表定义社团头衔表的信息';


-- 社团公告表
DROP TABLE IF EXISTS `ts_club_notice`;
CREATE TABLE `ts_club_notice` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键id.',
  `clubid` int(11) default NULL COMMENT '社团id.',
  `uid` int(11) default NULL COMMENT '用户id.',
  `content` varchar(255) default NULL COMMENT '公告内容.',
  `ctime` int(11) default NULL COMMENT '创建时间.',
  `isdel` int(1) default '0' COMMENT '是否删除.0未删除，1已删除',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社团公告表, 该表定义社团公告的信息';

-- 社团回复表
DROP TABLE IF EXISTS `ts_club_reply`;
CREATE TABLE `ts_club_reply` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键id.',
  `topicid` varchar(255) default NULL COMMENT '主题id.',
  `uid` varchar(255) default NULL COMMENT '作者id.',
  `content` text COMMENT '内容.',
  `replyid` int(11) default NULL COMMENT '回复的id.0回复论坛主题，非0回复本表对应的id',
  `replyuid` int(11) default NULL COMMENT '回复的作者.',
  `floor` int(11) default '0' COMMENT '楼层数.',
  `ctime` int(11) default NULL COMMENT '回复时间.',
  `isdel` int(1) default '0' COMMENT '是否删除.0未删除，1已删除',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社团回复表, 该表定义社团回复的信息';

-- 社团主题表
DROP TABLE IF EXISTS `ts_club_topic`;
CREATE TABLE `ts_club_topic` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键id.',
  `clubid` int(11) default NULL COMMENT '所属社团id.',
  `uid` int(11) default NULL COMMENT '作者uid.',
  `title` varchar(255) default NULL COMMENT '主题标题.',
  `type` int(1) default NULL COMMENT '主题类型.1帖子,2活动',
  `issue` int(11) default '0' COMMENT '若是活动，则保存活动的期数',
  `zding` int(1) default '0' COMMENT '是否置顶帖子.1是 0否',
  `tjian` int(1) default '0' COMMENT '是否推荐帖子.1是 0否',
  `topicpic` varchar(255) default null COMMENT '主题缩略图',
  `content` text COMMENT '内容.',
  `replycount` int(11) default '0' COMMENT '回复数.',
  `clickcount` int(11) default '0' COMMENT '点击数.',
  `ctime` int(11) default NULL COMMENT '发布时间.',
  `replytime` int(11) default NULL COMMENT '最后回复时间.',
  `replyuid` int(11) default NULL,
  `replyman` int(1) default '0' COMMENT '可回复的人.0所有人，1仅社团成员',
  `isdel` int(1) default '0' COMMENT '是否删除.0未删除，1已删除',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='社团主题表, 该表定义社团主题的信息';

-- 在顶部添加进入社团入口
DELETE FROM `ts_dsk_navbar` WHERE `navname` = '社团';
INSERT INTO `ts_dsk_navbar` (`navname`, `navicon`, `navicon_hover`, `navurl`, `type`, `target`, `disp`, `avaliable`, `isdefault`,`allow`,`margintop`,`marginright`,`marginbottom`,`marginleft`,`iconview`,`iconposition`,`direction`,`dockshow`,`topbarshow`,`autolist`,`scale`,`backimg`,`inuser`,`icos`,`wins`,`iconpositions`,`mids`) 
VALUES ('社团', '', '', 'index.php?app=club', 'custom', '_self', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '1', '0', '', '1', '', '', '', '');

