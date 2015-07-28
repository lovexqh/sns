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
-- 表的结构 `ts_square_category`
--

CREATE TABLE IF NOT EXISTS `ts_square_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL COMMENT '父类ID',
  `category_name` char(20) NOT NULL COMMENT '栏目名称',
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='广场栏目表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `ts_square_category`
--

INSERT INTO `ts_square_category` (`id`, `p_id`, `category_name`, `display_order`) VALUES
(1, 0, '文章', 1),
(2, 0, '视频', 2),
(3, 0, '资源', 3),
(4, 1, '学习资料', 2),
(5, 1, '天下杂谈', 4),
(6, 1, '幽默趣闻', 5),
(7, 1, '经典美文', 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
