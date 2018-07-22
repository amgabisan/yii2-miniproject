-- --------------------------------------------------------
-- Host:                         192.168.20.12
-- Server version:               5.6.40 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for yii2
DROP DATABASE IF EXISTS `yii2`;
CREATE DATABASE IF NOT EXISTS `yii2` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `yii2`;

-- Dumping structure for table yii2.auth
DROP TABLE IF EXISTS `auth`;
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `source_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_auth_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table yii2.question
DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `type` enum('single_free_input','multiple_free_input','single_choice','multiple_choice','rating') NOT NULL,
  `type_details` text COMMENT 'For single/multiple choice, CSV of choices while for rating, no. of stars',
  `required_flag` tinyint(4) NOT NULL COMMENT '1-required, 0-not required',
  `ins_time` datetime NOT NULL,
  `up_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `questionnaire_question_FK` (`questionnaire_id`),
  CONSTRAINT `questionnaire_question_FK` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table yii2.questionnaire
DROP TABLE IF EXISTS `questionnaire`;
CREATE TABLE IF NOT EXISTS `questionnaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ins_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `up_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_questionnaire` (`user_id`),
  CONSTRAINT `user_questionnaire` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table yii2.ua_details
DROP TABLE IF EXISTS `ua_details`;
CREATE TABLE IF NOT EXISTS `ua_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `ins_time` datetime NOT NULL,
  `up_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ua_details_fk` (`user_answer_id`),
  KEY `question_uad_fk` (`question_id`),
  CONSTRAINT `question_uad_fk` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ua_details_fk` FOREIGN KEY (`user_answer_id`) REFERENCES `user_answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table yii2.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','user') NOT NULL DEFAULT 'user',
  `auth_key` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email_address` varchar(100) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `status` enum('active','inactive','unconfirmed') NOT NULL,
  `ins_time` datetime DEFAULT NULL,
  `up_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf32;

-- Data exporting was unselected.
-- Dumping structure for table yii2.user_answer
DROP TABLE IF EXISTS `user_answer`;
CREATE TABLE IF NOT EXISTS `user_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(11) NOT NULL,
  `ins_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `up_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ua_questionnaire_fk` (`questionnaire_id`),
  CONSTRAINT `ua_questionnaire_fk` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
