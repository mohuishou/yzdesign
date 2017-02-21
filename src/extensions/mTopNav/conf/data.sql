/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : kehu

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-02-21 23:29:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pw_app_m_topnav
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_topnav`;
CREATE TABLE `pw_app_m_topnav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `float_type` int(2) DEFAULT '1' COMMENT '1:左边2：右边',
  `sort` int(5) DEFAULT '0',
  `a` varchar(255) DEFAULT NULL,
  `created_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
