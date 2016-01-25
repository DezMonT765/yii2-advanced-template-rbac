/*
Navicat MySQL Data Transfer

Source Server         : home
Source Server Version : 50545
Source Host           : localhost:3306
Source Database       : advanced_template

Target Server Type    : MYSQL
Target Server Version : 50545
File Encoding         : 65001

Date: 2016-01-17 20:02:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for source_message
-- ----------------------------
DROP TABLE IF EXISTS `source_message`;
CREATE TABLE `source_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(32) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
