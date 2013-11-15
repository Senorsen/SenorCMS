-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013-11-15 17:54:47
-- 服务器版本: 5.5.32-0ubuntu7
-- PHP 版本: 5.5.3-1ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `senor`
--
CREATE DATABASE IF NOT EXISTS `senor` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `senor`;

-- --------------------------------------------------------

--
-- 表的结构 `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `tb_article`
--

CREATE TABLE IF NOT EXISTS `tb_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(128) NOT NULL,
  `sort` varchar(128) NOT NULL DEFAULT '',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  `click_count` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  PRIMARY KEY (`id`),
  KEY `title` (`title`(333)),
  KEY `pubdate` (`pubdate`),
  KEY `sort` (`sort`),
  KEY `author` (`author`),
  KEY `hidden` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `tb_category_cache`
--

CREATE TABLE IF NOT EXISTS `tb_category_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '缓存ID',
  `cat_self` int(11) NOT NULL COMMENT '分类ID',
  `cat_descendants` varchar(5120) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '子孙们的ID',
  PRIMARY KEY (`id`),
  KEY `cat_self` (`cat_self`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `tb_category_def`
--

CREATE TABLE IF NOT EXISTS `tb_category_def` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` varchar(64) NOT NULL,
  `full_name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `short_name` (`short_name`,`full_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `tb_category_link`
--

CREATE TABLE IF NOT EXISTS `tb_category_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `tb_category_map`
--

CREATE TABLE IF NOT EXISTS `tb_category_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `child_id` (`child_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(256) NOT NULL COMMENT '用户名',
  `password` varchar(128) NOT NULL COMMENT '密码加盐',
  `hidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT '禁用',
  `regip` varchar(256) NOT NULL COMMENT '注册IP地址',
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册日期时间',
  `priviledge` int(11) NOT NULL DEFAULT '0' COMMENT '权限',
  `article_count` int(11) NOT NULL DEFAULT '0' COMMENT '发表文章数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `hidden` (`hidden`,`regip`,`regdate`),
  KEY `priviledge` (`priviledge`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
