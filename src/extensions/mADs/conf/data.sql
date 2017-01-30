/*
Navicat MariaDB Data Transfer

Source Server         : 虚拟机
Source Server Version : 100113
Source Host           : 192.168.56.101:3306
Source Database       : phpwind

Target Server Type    : MariaDB
Target Server Version : 100113
File Encoding         : 65001

Date: 2017-01-24 14:18:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pw_app_m_ads_item
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_ads_item`;
CREATE TABLE `pw_app_m_ads_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '广告类型',
  `link` text,
  `imgpath` varchar(255) DEFAULT NULL,
  `type_id` int(10) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `window_title` varchar(255) DEFAULT NULL,
  `click` int(100) DEFAULT NULL COMMENT '点击次数统计',
  `script` text,
  `created_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_ads_type
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_ads_type`;
CREATE TABLE `pw_app_m_ads_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT '广告类型',
  `name` varchar(255) DEFAULT NULL,
  `open_type` varchar(255) DEFAULT NULL,
  `click` int(100) DEFAULT NULL,
  `created_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
--安装或更新时需要注册的sql写在这里--