-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2013 年 04 月 30 日 12:44
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `test`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `gbook`
-- 

CREATE TABLE `gbook` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `content` varchar(140) NOT NULL,
  `re` varchar(200) NOT NULL,
  `rtime` int(10) unsigned NOT NULL default '0',
  `uip` varchar(20) NOT NULL,
  `ctime` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `gbook`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `weibo`
-- 

CREATE TABLE `weibo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `ctime` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `weibo`
-- 

INSERT INTO `weibo` VALUES (1, '欢迎使用WillBlog微博系统', '这是一个简捷快速的微博系统.\r\n\r\n[B]功能简介:[/B]\r\n    1.支持自定义皮肤,可以登录后在配置里设置0-9.\r\n    2.可修改网站的其它设置.\r\n    3.简单的留言功能.\r\n\r\n下载地址:[URL]http://www.113344.com/down/weibo_v1.1.rar[/URL]\r\n\r\n意见反馈: [URL]http://www.113344.com/index.php/Gbook/[/URL]', 1366113225);
INSERT INTO `weibo` VALUES (2, 'Willblog版本更新v1.2', '2013-04-30 版本v1.2\r\n优化重写了各种类.\r\n留言本加入回复功能\r\n添加了一管皮肤10\r\n可从配置设置管理用户和密码\r\n修正了一些错误\r\n\r\n下载地址:[URL]http://www.113344.com/down/weibo_v1.2.rar[/URL]\r\n\r\n演示地址: [URL]http://blog.113344.com/[/URL]', 1367325706);
