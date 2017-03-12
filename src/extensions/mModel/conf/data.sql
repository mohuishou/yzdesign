/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : kehu

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-03-12 02:25:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pw_app_m_model_category
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_category`;
CREATE TABLE `pw_app_m_model_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0',
  `tid` int(11) DEFAULT NULL COMMENT 'type_id',
  `name` varchar(255) DEFAULT NULL,
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_collection_box
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_collection_box`;
CREATE TABLE `pw_app_m_model_collection_box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL COMMENT '模型类型id',
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_collection_item
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_collection_item`;
CREATE TABLE `pw_app_m_model_collection_item` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `bid` int(11) DEFAULT NULL COMMENT 'box_id,收藏夹的id',
  `mid` int(11) DEFAULT NULL COMMENT '模型id',
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_download
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_download`;
CREATE TABLE `pw_app_m_model_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_file
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_file`;
CREATE TABLE `pw_app_m_model_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_item
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_item`;
CREATE TABLE `pw_app_m_model_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `style` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `pictures` varchar(255) DEFAULT NULL,
  `img_type` varchar(255) DEFAULT NULL,
  `light` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` int(2) DEFAULT '0',
  `file` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `liked` int(20) DEFAULT '0',
  `collection` int(20) DEFAULT '0',
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_picture
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_picture`;
CREATE TABLE `pw_app_m_model_picture` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pw_app_m_model_type
-- ----------------------------
DROP TABLE IF EXISTS `pw_app_m_model_type`;
CREATE TABLE `pw_app_m_model_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '模型种类名称：全景、施工图等等',
  `style` varchar(255) DEFAULT NULL COMMENT '风格设置，选项使用逗号分隔',
  `version` varchar(255) DEFAULT NULL COMMENT '版本设置，使用逗号分隔',
  `img_type` varchar(255) DEFAULT NULL COMMENT '材质贴图设置',
  `admin_only` tinyint(2) DEFAULT '0',
  `light` varchar(255) DEFAULT NULL COMMENT '灯光设置',
  `created_time` int(20) DEFAULT NULL,
  `updated_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
