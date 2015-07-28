-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 06 日 13:42
-- 服务器版本: 5.5.21
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `edusns_db`
--

-- --------------------------------------------------------

--
-- 表的结构 `ts_square_blog`
--

CREATE TABLE IF NOT EXISTS `ts_square_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `square_id` int(11) NOT NULL COMMENT '栏目分组id',
  `blog_id` int(11) NOT NULL COMMENT '文章id',
  `state` tinyint(1) DEFAULT '0' COMMENT '是否审核，1是，0否',
  `display_order` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `ts_square_blog`
--

INSERT INTO `ts_square_blog` (`id`, `square_id`, `blog_id`, `state`, `display_order`) VALUES
(2, 1, 3, 0, 0),
(15, 4, 23, 0, 1),
(16, 4, 24, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
