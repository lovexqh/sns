-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 08 月 27 日 08:50
-- 服务器版本: 5.0.91
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `thinksns_db`
--

-- --------------------------------------------------------

--
-- 表的结构 `ts_roster_student`
--

CREATE TABLE IF NOT EXISTS `ts_roster_student` (
  `id` int(11) NOT NULL auto_increment,
  `classname` varchar(100) default NULL,
  `seatno` varchar(100) default NULL,
  `name` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL default '0',
  `userno` varchar(100) NOT NULL,
  `identity` varchar(18) default NULL,
  `birthday` varchar(10) default NULL,
  `oldname` varchar(100) default NULL,
  `nation` varchar(50) default NULL,
  `comefrom` varchar(100) default NULL,
  `studying` varchar(100) default NULL,
  `studenttype` varchar(100) default NULL,
  `familystatus` varchar(100) default NULL,
  `lowfamilycard` varchar(18) default NULL,
  `lowfamilytype` varchar(50) default NULL,
  `household` varchar(100) default NULL,
  `housecity` varchar(100) default NULL,
  `houseaddress` varchar(255) default NULL,
  `postalcode` varchar(6) default NULL,
  `nowaddress` varchar(255) default NULL,
  `nativeplace` varchar(100) default NULL,
  `ismigrantworkers` tinyint(1) NOT NULL default '0',
  `migrantworkerstype` varchar(50) default NULL,
  `migrantworkersfrom` varchar(100) default NULL,
  `isleftbehind` tinyint(1) NOT NULL default '0',
  `leftbehindsituation` varchar(255) default NULL,
  `leftbehindentrust` varchar(255) default NULL,
  `country` varchar(50) default NULL,
  `countryother` varchar(50) default NULL,
  `certificatesno` varchar(100) default NULL,
  `telephone` varchar(255) default NULL,
  `guardianname` varchar(20) default NULL,
  `father` varchar(20) default NULL,
  `mother` varchar(20) default NULL,
  `fatherwork` varchar(200) default NULL,
  `motherwork` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `seatno` (`seatno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
