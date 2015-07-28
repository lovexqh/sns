--
-- 表的结构 `ts_dsk_appclass`
--

DROP TABLE IF EXISTS `ts_dsk_appclass`;
CREATE TABLE `ts_dsk_appclass` (
  `classid` smallint(6) unsigned NOT NULL auto_increment,
  `fupid` smallint(6) unsigned NOT NULL default '0',
  `classname` varchar(255) NOT NULL,
  `classico` varchar(255) NOT NULL,
  `appnum` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `disp` int(10) unsigned NOT NULL default '0',
  `appids` text NOT NULL,
  PRIMARY KEY  (`classid`),
  KEY `dateline` (`dateline`),
  KEY `disp` (`disp`),
  KEY `fupid` (`fupid`),
  KEY `classname` (`classname`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_apps`
--

DROP TABLE IF EXISTS `ts_dsk_apps`;
CREATE TABLE `ts_dsk_apps` (
  `appid` int(10) unsigned NOT NULL auto_increment,
  `appname` varchar(255) NOT NULL,
  `appico` varchar(255) NOT NULL,
  `appdesc` text NOT NULL,
  `appurl` varchar(255) NOT NULL,
  `width` mediumint(8) unsigned NOT NULL default '0',
  `height` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `usenum` mediumint(8) unsigned NOT NULL default '0',
  `replynum` mediumint(8) unsigned NOT NULL default '0',
  `star` float(2,1) unsigned NOT NULL,
  `starnum` mediumint(8) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `classid` smallint(6) unsigned NOT NULL default '0',
  `classids` text NOT NULL,
  `default` tinyint(1) NOT NULL default '0',
  `hot` int(10) unsigned NOT NULL default '0',
  `open` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `disp` int(10) unsigned NOT NULL default '0',
  `ismarket` tinyint(1) unsigned NOT NULL default '0',
  `vendor` varchar(255) NOT NULL,
  `setupnum` int(10) NOT NULL default '0',
  `oid` int(10) unsigned NOT NULL default '0',
  `haveflash` tinyint(1) unsigned NOT NULL,
  `sitesetupnum` int(10) unsigned NOT NULL default '0',
  `defaultopen` tinyint(1) unsigned NOT NULL default '0',
  `notdelete` tinyint(1) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `lang` tinyint(1) unsigned NOT NULL default '0',
  `ischeck` tinyint(1) unsigned NOT NULL default '3',
  `idtype` varchar(255) NOT NULL,
  `typeid` int(10) unsigned NOT NULL default '0',
  `titlebuttons` varchar(255) NOT NULL default 'home,refresh,detail,min,max,close',
  `isshow` tinyint(1) unsigned NOT NULL default '1',
  `havetask` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`appid`),
  KEY `disp` (`default`),
  KEY `classid` (`classid`),
  KEY `hot` (`hot`),
  KEY `uid` (`uid`),
  KEY `sitesetupnum` (`sitesetupnum`),
  KEY `appurl` (`appurl`),
  KEY `defaultopen` (`defaultopen`,`notdelete`),
  KEY `type` (`type`),
  KEY `idtype` (`typeid`,`idtype`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_attach`
--

DROP TABLE IF EXISTS `ts_dsk_attach`;
CREATE TABLE `ts_dsk_attach` (
  `qid` int(10) unsigned NOT NULL auto_increment,
  `dateline` int(10) unsigned NOT NULL default '0',
  `filename` char(100) NOT NULL default '',
  `filetype` char(50) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `attachment` char(100) NOT NULL default '',
  `downloads` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `remote` tinyint(1) unsigned NOT NULL default '0',
  `copys` smallint(6) NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `aid` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  `desc` text NOT NULL,
  `img` varchar(255) NOT NULL,
  `viewnum` int(10) unsigned NOT NULL default '0',
  `replynum` int(10) unsigned NOT NULL default '0',
  `star` float(2,1) NOT NULL default '0.0',
  `starnum` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`qid`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM ;

--
-- 表的结构 `ts_dsk_attachment`
--

DROP TABLE IF EXISTS `ts_dsk_attachment`;
CREATE TABLE `ts_dsk_attachment` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `dateline` int(10) unsigned NOT NULL default '0',
  `filename` char(100) NOT NULL default '',
  `filetype` char(50) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `attachment` char(100) NOT NULL default '',
  `downloads` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `remote` tinyint(1) unsigned NOT NULL default '0',
  `copys` smallint(6) NOT NULL default '0',
  `md5` varchar(255) NOT NULL,
  `thumb` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `uid` (`uid`),
  UNIQUE KEY `md5` (`md5`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_cimage`
--

DROP TABLE IF EXISTS `ts_dsk_cimage`;
CREATE TABLE `ts_dsk_cimage` (
  `cid` mediumint(8) NOT NULL auto_increment,
  `aid` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `size` int(10) unsigned NOT NULL default '0',
  `filepath` varchar(255) NOT NULL default '',
  `thumb` tinyint(1) NOT NULL default '0',
  `remote` tinyint(1) NOT NULL default '0',
  `copys` int(10) unsigned NOT NULL default '0',
  `ourl` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `ourl` (`ourl`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_clink`
--

DROP TABLE IF EXISTS `ts_dsk_clink`;
CREATE TABLE `ts_dsk_clink` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `copys` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_cmusic`
--

DROP TABLE IF EXISTS `ts_dsk_cmusic`;
CREATE TABLE `ts_dsk_cmusic` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `ourl` varchar(255) NOT NULL,
  `copys` int(10) unsigned NOT NULL default '0',
  `aid` int(10) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  `ext` varchar(255) NOT NULL,
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `ourl` (`ourl`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_comment`
--

DROP TABLE IF EXISTS `ts_dsk_comment`;
CREATE TABLE `ts_dsk_comment` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `pcid` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `idtype` varchar(20) NOT NULL default '',
  `authorid` mediumint(8) unsigned NOT NULL default '0',
  `author` varchar(15) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `message` text NOT NULL,
  `magicflicker` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `zhichi` int(10) unsigned NOT NULL default '0',
  `fandui` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `pcid` (`pcid`),
  KEY `authorid` (`authorid`),
  KEY `id` (`id`,`idtype`),
  KEY `dateline` (`dateline`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_config`
--

DROP TABLE IF EXISTS `ts_dsk_config`;
CREATE TABLE `ts_dsk_config` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `userscreennum` tinyint(1) unsigned NOT NULL default '5',
  `loginurl` varchar(255) NOT NULL default 'member.php?mod=logging&action=login',
  `logouturl` varchar(255) NOT NULL default 'member.php?mod=logging&action=logout',
  `marketurl` varchar(255) NOT NULL default 'dzz.php?mod=market',
  `widgeturl` varchar(255) NOT NULL default 'dzz.php?mod=widget',
  `addappurl` varchar(255) NOT NULL default 'dzz.php?mod=market&op=addapp',
  `systhameurl` varchar(255) NOT NULL default 'dzz.php?mod=thame',
  `sysbrowserurl` varchar(255) NOT NULL default 'about:blank',
  `defaultdesktop` tinyint(1) unsigned NOT NULL default '1',
  `sitename` varchar(255) NOT NULL default 'Dzz Desktop 1.2',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `tongji` text NOT NULL,
  `copyright` text NOT NULL,
  `dataurl` varchar(255) NOT NULL default 'dzz.php?mod=system&op=json',
  `marginleft` smallint(6) unsigned NOT NULL default '10',
  `marginright` smallint(6) unsigned NOT NULL default '10',
  `margintop` smallint(6) unsigned NOT NULL default '40',
  `marginbottom` smallint(6) unsigned NOT NULL default '70',
  `dockshow` tinyint(1) unsigned NOT NULL default '0',
  `topbarshow` tinyint(1) unsigned NOT NULL default '0',
  `iconposition` tinyint(1) unsigned NOT NULL default '1',
  `iconview` tinyint(1) unsigned NOT NULL default '2',
  `autolist` tinyint(1) unsigned NOT NULL default '1',
  `siteuniqueid` varchar(255)  NOT NULL default '',
  `leavealert` tinyint(1) unsigned NOT NULL default '0',
  `spacebuy` tinyint(1) unsigned NOT NULL default '0',
  `buycredits` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_cvideo`
--

DROP TABLE IF EXISTS `ts_dsk_cvideo`;
CREATE TABLE `ts_dsk_cvideo` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `ourl` varchar(255) NOT NULL,
  `copys` int(10) unsigned NOT NULL default '0',
  `aid` int(10) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  `ext` varchar(255) NOT NULL,
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `ourl` (`ourl`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_folder`
--

DROP TABLE IF EXISTS `ts_dsk_folder`;
CREATE TABLE `ts_dsk_folder` (
  `fid` int(10) unsigned NOT NULL auto_increment,
  `fname` varchar(255) NOT NULL,
  `ficon` varchar(255) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `pfid` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) NOT NULL,
  `iconview` smallint(6) unsigned NOT NULL default '1',
  `ids` text NOT NULL,
  `desktop` varchar(60) NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM ;

--
-- 表的结构 `ts_dsk_icon`
--

DROP TABLE IF EXISTS `ts_dsk_icon`;
CREATE TABLE `ts_dsk_icon` (
  `did` int(10) unsigned NOT NULL auto_increment,
  `domain` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL default '0',
  `check` tinyint(1) unsigned NOT NULL default '1',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `copys` int(10) NOT NULL default '0',
  `disp` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`did`),
  KEY `domain` (`domain`),
  KEY `check` (`check`),
  KEY `uid` (`uid`),
  KEY `copys` (`copys`),
  KEY `disp` (`disp`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_iconview`
--

DROP TABLE IF EXISTS `ts_dsk_iconview`;
CREATE TABLE `ts_dsk_iconview` (
  `id` smallint(6) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `width` smallint(6) unsigned NOT NULL default '64',
  `height` smallint(6) unsigned NOT NULL default '64',
  `divwidth` smallint(6) unsigned NOT NULL default '100',
  `divheight` smallint(6) unsigned NOT NULL default '100',
  `paddingtop` smallint(6) unsigned NOT NULL default '0',
  `paddingleft` smallint(6) unsigned NOT NULL default '0',
  `textlength` smallint(6) unsigned NOT NULL default '30',
  `align` tinyint(1) unsigned NOT NULL default '0',
  `avaliable` tinyint(1) unsigned NOT NULL default '1',
  `disp` smallint(6) unsigned NOT NULL default '0',
  `cssname` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `avaliable` (`avaliable`,`disp`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_icos`
--

DROP TABLE IF EXISTS `ts_dsk_icos`;
CREATE TABLE `ts_dsk_icos` (
  `icoid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` int(10) NOT NULL default '0',
  `username` char(15) NOT NULL default '',
  `oid` mediumint(8) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `type` varchar(30) NOT NULL default '',
  `ext` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `wwidth` smallint(6) unsigned NOT NULL default '0',
  `wheight` smallint(6) unsigned NOT NULL default '0',
  `open` tinyint(1) unsigned NOT NULL default '0',
  `haveflash` tinyint(1) unsigned NOT NULL default '0',
  `history` tinyint(1) unsigned NOT NULL default '0',
  `notdelete` tinyint(1) unsigned NOT NULL default '0',
  `desktop` smallint(6) NOT NULL default '0',
  `defaultopen` tinyint(1) unsigned NOT NULL default '0',
  `idtype` varchar(255) NOT NULL,
  `typeid` int(10) unsigned NOT NULL default '0',
  `havetask` tinyint(1) unsigned NOT NULL default '1',
  `isshow` tinyint(1) unsigned NOT NULL default '1',
  `titlebuttons` varchar(255) NOT NULL default 'home,refresh,detail,min,max,close',
  PRIMARY KEY  (`icoid`),
  KEY `uid` (`uid`,`dateline`),
  KEY `dateline` (`dateline`),
  KEY `oid` (`oid`),
  KEY `idtype` (`idtype`),
  KEY `typeid` (`typeid`),
  KEY `type` (`type`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_image`
--

DROP TABLE IF EXISTS `ts_dsk_image`;
CREATE TABLE `ts_dsk_image` (
  `picid` mediumint(8) NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `aid` int(10) unsigned NOT NULL default '0',
  `postip` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `title` text NOT NULL,
  `type` varchar(255) NOT NULL default '',
  `size` int(10) unsigned NOT NULL default '0',
  `filepath` varchar(255) NOT NULL default '',
  `thumb` tinyint(1) NOT NULL default '0',
  `remote` tinyint(1) NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `replynum` int(10) unsigned NOT NULL,
  `copys` int(10) NOT NULL default '0',
  `star` float(2,1) NOT NULL,
  `starnum` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`picid`),
  KEY `uid` (`uid`),
  KEY `replynum` (`replynum`),
  KEY `viewnum` (`viewnum`),
  KEY `copys` (`copys`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_link`
--

DROP TABLE IF EXISTS `ts_dsk_link`;
CREATE TABLE `ts_dsk_link` (
  `lid` mediumint(8) unsigned NOT NULL auto_increment,
  `tagids` text NOT NULL,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `replynum` int(10) unsigned NOT NULL default '0',
  `copys` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(60) NOT NULL,
  `star` float(2,1) NOT NULL,
  `starnum` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  `did` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`),
  KEY `dateline` (`dateline`),
  KEY `viewnum` (`viewnum`),
  KEY `replynum` (`replynum`),
  KEY `copys` (`copys`),
  KEY `uid` (`uid`),
  KEY `starnum` (`starnum`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_music`
--

DROP TABLE IF EXISTS `ts_dsk_music`;
CREATE TABLE `ts_dsk_music` (
  `mid` mediumint(8) unsigned NOT NULL auto_increment,
  `tagids` text NOT NULL,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `ext` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `replynum` int(10) unsigned NOT NULL default '0',
  `copys` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(60) NOT NULL,
  `aid` int(10) unsigned NOT NULL default '0',
  `star` float(2,1) NOT NULL,
  `starnum` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `dateline` (`dateline`),
  KEY `viewnum` (`viewnum`),
  KEY `replynum` (`replynum`),
  KEY `copys` (`copys`),
  KEY `uid` (`uid`),
  KEY `starnum` (`starnum`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_navbar`
--
DROP TABLE IF EXISTS `ts_dsk_navbar`;
CREATE TABLE `ts_dsk_navbar` (
  `navid` smallint(6) NOT NULL auto_increment,
  `navname` varchar(255) NOT NULL,
  `navicon` varchar(255) NOT NULL,
  `navicon_hover` varchar(255) NOT NULL,
  `navurl` varchar(255) NOT NULL,
  `type` enum('desktop','custom') NOT NULL default 'desktop',
  `target` enum('_blank','_self') NOT NULL,
  `disp` int(10) unsigned NOT NULL,
  `avaliable` tinyint(1) unsigned NOT NULL default '1' ,
  `isdefault` tinyint(1) NOT NULL default '0',
  `allow` tinyint(1) unsigned NOT NULL default '0',
  `margintop` smallint(6) NOT NULL default '0',
  `marginright` smallint(6) NOT NULL default '0',
  `marginbottom` smallint(6) NOT NULL default '0',
  `marginleft` smallint(6) NOT NULL default '0',
  `iconview` smallint(6) unsigned NOT NULL default '0',
  `iconposition` tinyint(1) unsigned NOT NULL default '0' ,
  `dockshow` tinyint(1) unsigned NOT NULL default '0',
  `topbarshow` tinyint(1) unsigned NOT NULL default '0',
  `autolist` tinyint(1) unsigned NOT NULL default '1',
  `backimg` varchar(255) NOT NULL,
  PRIMARY KEY  (`navid`),
  KEY `type` (`type`,`disp`),
  KEY `disp` (`disp`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_pic`
--

DROP TABLE IF EXISTS `ts_dsk_pic`;
CREATE TABLE `ts_dsk_pic` (
  `picid` mediumint(8) NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `idtype` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `postip` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `size` int(10) unsigned NOT NULL default '0',
  `filepath` varchar(255) NOT NULL default '',
  `thumb` tinyint(1) NOT NULL default '0',
  `remote` tinyint(1) NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `zan` int(10) unsigned NOT NULL default '0',
  `checked` tinyint(1) unsigned NOT NULL default '0',
  `source` varchar(255) NOT NULL default '',
  `replynum` int(10) unsigned NOT NULL default '0',
  `cover` tinyint(1) NOT NULL default '0',
  `aid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`picid`),
  KEY `uid` (`uid`),
  KEY `source` (`source`),
  KEY `idtype` (`id`,`idtype`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_plugin`
--

DROP TABLE IF EXISTS `ts_dsk_plugin`;
CREATE TABLE `ts_dsk_plugin` (
  `pluginid` smallint(6) unsigned NOT NULL auto_increment,
  `available` tinyint(1) NOT NULL default '0',
  `adminid` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(40) NOT NULL default '',
  `identifier` varchar(40) NOT NULL default '',
  `directory` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `copyright` varchar(100) NOT NULL default '',
  `version` varchar(20) NOT NULL default '',
  `acceptdata` tinyint(1) unsigned NOT NULL default '0',
  `datatype` varchar(255) NOT NULL,
  `open` tinyint(1) NOT NULL default '0',
  `wwidth` smallint(6) unsigned NOT NULL default '0',
  `wheight` smallint(6) unsigned NOT NULL default '0',
  `titlebuttons` varchar(255) NOT NULL default 'home,refresh,detail,min,max,restore,close',
  `icon` varchar(255) NOT NULL,
  `disp` smallint(6) unsigned NOT NULL default '0',
  `indexfile` varchar(255) NOT NULL,
  `adminfile` varchar(255) NOT NULL,
  `installtype` varchar(255) NOT NULL,
  `appid` int(10) unsigned NOT NULL default '0',
  `isshow` tinyint(1) unsigned NOT NULL default '1',
  `havetask` tinyint(1) unsigned NOT NULL default '1',
  `wid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pluginid`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `disp` (`disp`),
  KEY `appid` (`appid`),
  KEY `wid` (`wid`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_recomment`
--

DROP TABLE IF EXISTS `ts_dsk_recomment`;
CREATE TABLE `ts_dsk_recomment` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `type` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `appid` int(10) unsigned NOT NULL default '0',
  `img` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `available` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `disp` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tid`,`type`),
  KEY `appid` (`appid`),
  KEY `type` (`type`),
  KEY `dateline` (`dateline`),
  KEY `available` (`available`),
  KEY `disp` (`disp`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_relative`
--

DROP TABLE IF EXISTS `ts_dsk_relative`;
CREATE TABLE `ts_dsk_relative` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `appid` int(10) unsigned NOT NULL default '0',
  `classid` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `appid` (`appid`),
  KEY `uid` (`classid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_score`
--

DROP TABLE IF EXISTS `ts_dsk_score`;
CREATE TABLE `ts_dsk_score` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `idtype` varchar(255) NOT NULL default 'appid',
  `star` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `uid` (`uid`),
  KEY `id` (`id`,`idtype`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_search`
--

DROP TABLE IF EXISTS `ts_dsk_search`;
CREATE TABLE `ts_dsk_search` (
  `srid` int(10) unsigned NOT NULL auto_increment,
  `keyword` varchar(255) NOT NULL,
  `sum` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`srid`),
  KEY `keyword` (`keyword`),
  KEY `sum` (`sum`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_sysicon`
--

DROP TABLE IF EXISTS `ts_dsk_sysicon`;
CREATE TABLE `ts_dsk_sysicon` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `type` enum('folder','url') NOT NULL,
  `copys` int(10) unsigned NOT NULL,
  `disp` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `default` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `disp` (`disp`),
  KEY `default` (`default`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_tagrelative`
--

DROP TABLE IF EXISTS `ts_dsk_tagrelative`;
CREATE TABLE `ts_dsk_tagrelative` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `tagid` int(10) unsigned NOT NULL default '0',
  `oid` smallint(6) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`rid`),
  KEY `tagid` (`tagid`),
  KEY `oid` (`oid`),
  KEY `type` (`type`,`rid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_tags`
--

DROP TABLE IF EXISTS `ts_dsk_tags`;
CREATE TABLE `ts_dsk_tags` (
  `tagid` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `tagname` varchar(50) NOT NULL default '',
  `disp` smallint(6) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `fup` smallint(6) NOT NULL default '0',
  `hot` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tagid`),
  KEY `disp` (`disp`),
  KEY `hot` (`hot`),
  KEY `type` (`type`,`tagid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_thame`
--

DROP TABLE IF EXISTS `ts_dsk_thame`;
CREATE TABLE `ts_dsk_thame` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL default 'mac',
  `backimg` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `btype` tinyint(1) NOT NULL default '1',
  `url` varchar(255) NOT NULL,
  `dock` varchar(255) NOT NULL,
  `window` varchar(255) NOT NULL,
  `default` tinyint(1) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `disp` smallint(6) unsigned NOT NULL default '0',
  `browser` varchar(255) NOT NULL default 'mac',
  `topbar` varchar(255) NOT NULL default 'mac',
  `filemanage` varchar(255) NOT NULL default 'mac',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `folder` (`folder`),
  KEY `disp` (`disp`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_thameitem`
--

DROP TABLE IF EXISTS `ts_dsk_thameitem`;
CREATE TABLE `ts_dsk_thameitem` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `idtype` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `disp` smallint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idtype` (`idtype`),
  KEY `disp` (`disp`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_userconfig`
--

DROP TABLE IF EXISTS `ts_dsk_userconfig`;
CREATE TABLE `ts_dsk_userconfig` (
  `uid` mediumint(8) unsigned NOT NULL,
  `username` varchar(255) NOT NULL default '',
  `docklist` text NOT NULL,
  `screenlist` text NOT NULL,
  `iconpositions` text NOT NULL,
  `thame` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `custom_backimg` varchar(255) NOT NULL,
  `custom_url` varchar(255) NOT NULL,
  `custom_window` varchar(255) NOT NULL,
  `custom_dock` varchar(255) NOT NULL,
  `custom_btype` tinyint(1) unsigned NOT NULL,
  `custom_browser` varchar(255) NOT NULL default '',
  `custom_filemanage` varchar(255) NOT NULL,
  `custom_topbar` varchar(255) NOT NULL,
  `spacename` varchar(255) NOT NULL,
  `metakeyword` varchar(255) NOT NULL,
  `metadescription` varchar(255) NOT NULL,
  `friend` tinyint(1) unsigned NOT NULL default '0',
  `current` varchar(255) NOT NULL default '0',
  `ukey` varchar(16) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `usesize` bigint(20) unsigned NOT NULL default '0',
  UNIQUE KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `viewnum` (`viewnum`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_userconfig_field`
--

DROP TABLE IF EXISTS `ts_dsk_userconfig_field`;
CREATE TABLE `ts_dsk_userconfig_field` (
  `uid` int(10) NOT NULL default '0',
  `allownewfolder` tinyint(1) NOT NULL default '-1',
  `allownewlink` tinyint(1) NOT NULL default '-1',
  `allowupload` tinyint(1) NOT NULL default '-1',
  `attachextensions` varchar(255) NOT NULL default '-1',
  `maxattachsize` int(10) NOT NULL default '-1',
  `addsize` bigint(20) unsigned NOT NULL default '0',
  `buysize` bigint(20) unsigned NOT NULL default '0',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_userdo`
--

DROP TABLE IF EXISTS `ts_dsk_userdo`;
CREATE TABLE `ts_dsk_userdo` (
  `doid` int(10) unsigned NOT NULL auto_increment,
  `appid` int(10) unsigned NOT NULL default '0',
  `type` enum('view','setup','score','comment','open') NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`doid`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`),
  KEY `type` (`type`),
  KEY `appid` (`appid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_usergroup`
--

DROP TABLE IF EXISTS `ts_dsk_usergroup`;
CREATE TABLE `ts_dsk_usergroup` (
  `groupid` smallint(6) unsigned NOT NULL default '0',
  `maxspacesize` int(10) unsigned NOT NULL default '0',
  `attachextensions` varchar(255) NOT NULL,
  `maxattachsize` int(10) unsigned NOT NULL default '0',
  `allownewfolder` tinyint(1) unsigned NOT NULL default '1',
  `allownewlink` tinyint(1) unsigned NOT NULL default '1',
  `allowupload` tinyint(1) unsigned NOT NULL default '1',
  UNIQUE KEY `groupid` (`groupid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_usericon`
--

DROP TABLE IF EXISTS `ts_dsk_usericon`;
CREATE TABLE `ts_dsk_usericon` (
  `did` int(10) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `domain` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL default '0',
  `pdid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`did`),
  UNIQUE KEY `domain` (`domain`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_video`
--

DROP TABLE IF EXISTS `ts_dsk_video`;
CREATE TABLE `ts_dsk_video` (
  `vid` mediumint(8) unsigned NOT NULL auto_increment,
  `tagids` text NOT NULL,
  `img` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `ext` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `viewnum` int(10) unsigned NOT NULL default '0',
  `replynum` int(10) unsigned NOT NULL default '0',
  `copys` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(60) NOT NULL,
  `aid` int(10) unsigned NOT NULL default '0',
  `star` float(2,1) NOT NULL,
  `starnum` int(10) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `friend` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`vid`),
  KEY `dateline` (`dateline`),
  KEY `viewnum` (`viewnum`),
  KEY `replynum` (`replynum`),
  KEY `copys` (`copys`),
  KEY `uid` (`uid`),
  KEY `starnum` (`starnum`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_wallpaper`
--

DROP TABLE IF EXISTS `ts_dsk_wallpaper`;
CREATE TABLE `ts_dsk_wallpaper` (
  `bid` smallint(6) unsigned NOT NULL auto_increment,
  `type` char(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `val` varchar(255) NOT NULL,
  `classid` smallint(6) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `disp` smallint(6) unsigned NOT NULL default '0',
  `thumb` tinyint(1) unsigned NOT NULL default '0',
  `img` varchar(255) NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `classid` (`classid`),
  KEY `disp` (`disp`),
  KEY `type` (`type`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_wallpaper_class`
--

DROP TABLE IF EXISTS `ts_dsk_wallpaper_class`;
CREATE TABLE `ts_dsk_wallpaper_class` (
  `classid` smallint(6) unsigned NOT NULL auto_increment,
  `classname` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `disp` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`classid`),
  KEY `disp` (`disp`),
  KEY `type` (`type`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_widget`
--

DROP TABLE IF EXISTS `ts_dsk_widget`;
CREATE TABLE `ts_dsk_widget` (
  `gid` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `width` smallint(6) unsigned NOT NULL default '100',
  `height` smallint(6) unsigned NOT NULL default '100',
  `href` varchar(255) NOT NULL,
  `open` tinyint(1) NOT NULL default '0',
  `dateline` int(10) NOT NULL default '0',
  `uid` int(10) NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `classname` varchar(255) NOT NULL,
  `idtype` varchar(255) NOT NULL,
  `typeid` int(10) unsigned NOT NULL default '0',
  `notdelete` tinyint(1) unsigned NOT NULL default '0',
  `oid` int(10) unsigned NOT NULL default '0',
  `desktop` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`gid`),
  KEY `typeid` (`typeid`,`idtype`),
  KEY `oid` (`oid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_widget_class`
--

DROP TABLE IF EXISTS `ts_dsk_widget_class`;
CREATE TABLE `ts_dsk_widget_class` (
  `classid` smallint(6) unsigned NOT NULL auto_increment,
  `fupid` smallint(6) unsigned NOT NULL default '0',
  `classname` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `disp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`classid`),
  KEY `disp` (`disp`),
  KEY `fupid` (`fupid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_widget_market`
--

DROP TABLE IF EXISTS `ts_dsk_widget_market`;
CREATE TABLE `ts_dsk_widget_market` (
  `wid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `width` mediumint(8) unsigned NOT NULL default '0',
  `height` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `usenum` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL,
  `classid` smallint(6) unsigned NOT NULL default '0',
  `classids` text NOT NULL,
  `default` tinyint(1) NOT NULL default '0' ,
  `open` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `disp` int(10) unsigned NOT NULL default '0',
  `setupnum` int(10) NOT NULL default '0',
  `notdelete` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `idtype` varchar(255) NOT NULL,
  `typeid` int(10) unsigned NOT NULL default '0',
  `classname` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  PRIMARY KEY  (`wid`),
  KEY `disp` (`default`),
  KEY `classid` (`classid`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `idtype` (`typeid`,`idtype`),
  KEY `setupnum` (`setupnum`)
) ENGINE=MyISAM ;

--
-- 表的结构 `ts_dsk_widget_relative`
--

DROP TABLE IF EXISTS `ts_dsk_widget_relative`;
CREATE TABLE `ts_dsk_widget_relative` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `wid` int(10) unsigned NOT NULL default '0',
  `classid` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `wid` (`wid`),
  KEY `classid` (`classid`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_space_product`
--

DROP TABLE IF EXISTS `ts_dsk_space_product`;
CREATE TABLE `ts_dsk_space_product` (
  `pid` smallint(6) NOT NULL auto_increment,
  `pname` varchar(255) NOT NULL,
  `danwei` enum('day','month','year') NOT NULL,
  `description` text NOT NULL,
  `available` tinyint(1) NOT NULL default '0',
  `disp` smallint(6) NOT NULL default '0',
  `price` int(10) NOT NULL default '0',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '10',
  `spacesize` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`pid`),
  KEY `disp` (`disp`),
  KEY `starttime` (`starttime`),
  KEY `endtime` (`endtime`)
) ENGINE=MyISAM;

--
-- 表的结构 `ts_dsk_space_record`
--

DROP TABLE IF EXISTS `ts_dsk_space_record`;
CREATE TABLE `ts_dsk_space_record` (
  `rid` int(10) NOT NULL auto_increment,
  `pname` varchar(255) NOT NULL,
  `endtime` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `ip` varchar(255) NOT NULL,
  `uid` int(10) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `danwei` enum('day','month','year') NOT NULL,
  `num` smallint(6) unsigned NOT NULL default '0',
  `price` int(10) NOT NULL default '0',
  `spacesize` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;


--
-- 表的结构 `ts_dsk_space_record_log`
--

DROP TABLE IF EXISTS `ts_dsk_space_record_log`;
CREATE TABLE `ts_dsk_space_record_log` (
  `rid` int(10) NOT NULL auto_increment,
  `pname` varchar(255) NOT NULL,
  `endtime` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `ip` varchar(255) NOT NULL,
  `uid` int(10) unsigned NOT NULL default '0',
  `username` varchar(255) NOT NULL,
  `danwei` enum('day','month','year') NOT NULL,
  `num` smallint(6) unsigned NOT NULL default '0',
  `price` int(10) NOT NULL default '0',
  `spacesize` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rid`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;

--
-- 导出表中的数据 `ts_dsk_iconview`
--

INSERT INTO `ts_dsk_iconview` (`id`, `name`, `width`, `height`, `divwidth`, `divheight`, `paddingtop`, `paddingleft`, `textlength`, `align`, `avaliable`, `disp`, `cssname`) VALUES
(1, '大图标', 100, 100, 155, 160, 30, 30, 50, 0, 1, 1, 'bigicon'),
(2, '中图标', 50, 50, 100, 103, 20, 20, 40, 0, 1, 2, 'middleicon'),
(3, '中图标列表', 50, 50, 180, 70, 20, 20, 40, 1, 1, 3, 'middlelist'),
(4, '小图标列表', 32, 32, 220, 42, 20, 20, 36, 1, 1, 4, 'smalllist');