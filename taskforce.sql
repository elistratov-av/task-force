/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50723
Source Host           : localhost:3306
Source Database       : taskforce

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2019-10-01 22:05:48
*/
CREATE SCHEMA IF NOT EXISTS taskforce DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE taskforce;


SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bookmarks
-- ----------------------------
DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `performer_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bookmarks_users_1` (`user_id`),
  KEY `fk_bookmarks_users_2` (`performer_id`),
  CONSTRAINT `fk_bookmarks_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_bookmarks_users_2` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cities
-- ----------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lat` double NOT NULL,
  `long` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `type` char(12) NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_events_users_1` (`user_id`),
  KEY `fk_events_tasks_1` (`task_id`),
  CONSTRAINT `fk_events_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `fk_events_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`),
  KEY `fk_files_tasks_1` (`task_id`),
  KEY `fk_files_users_1` (`user_id`),
  CONSTRAINT `fk_files_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `fk_files_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `recipient_id` int(11) unsigned NOT NULL,
  `sender_id` int(11) unsigned NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `task_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_messages_tasks_1` (`task_id`),
  KEY `fk_messages_users_1` (`recipient_id`),
  KEY `fk_messages_users_2` (`sender_id`),
  CONSTRAINT `fk_messages_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `fk_messages_users_1` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_messages_users_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for opinions
-- ----------------------------
DROP TABLE IF EXISTS `opinions`;
CREATE TABLE `opinions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) unsigned NOT NULL,
  `performer_id` int(11) unsigned NOT NULL,
  `rate` tinyint(1) unsigned NOT NULL,
  `description` text NOT NULL,
  `dt_add` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_opinions_users_1` (`owner_id`),
  KEY `fk_opinions_users_2` (`performer_id`),
  CONSTRAINT `fk_opinions_users_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_opinions_users_2` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `opinions` ADD `task_id` INT UNSIGNED NOT NULL;
ALTER TABLE `opinions` ADD CONSTRAINT `fk_opinions_tasks` FOREIGN KEY (id) REFERENCES `tasks`(id);

-- ----------------------------
-- Table structure for replies
-- ----------------------------
DROP TABLE IF EXISTS `replies`;
CREATE TABLE `replies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(255) NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `is_approved` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_replies_users_1` (`user_id`),
  KEY `fk_replies_tasks_1` (`task_id`),
  CONSTRAINT `fk_replies_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  CONSTRAINT `fk_replies_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for statuses
-- ----------------------------
DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `budget` int(10) unsigned DEFAULT NULL,
  `expire_dt` datetime DEFAULT NULL,
  `dt_add` datetime DEFAULT CURRENT_TIMESTAMP,
  `client_id` int(10) unsigned NOT NULL,
  `performer_id` int(10) unsigned DEFAULT NULL,
  `status_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tasks_categories_1` (`category_id`),
  KEY `fk_tasks_statuses_1` (`status_id`),
  CONSTRAINT `fk_tasks_categories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_tasks_statuses_1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `password` char(64) NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `last_activity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_cities_1` (`city_id`),
  CONSTRAINT `fk_users_cities_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users` ADD `avatar` varchar(255) NULL;
ALTER TABLE `users` ADD `bd_date` DATETIME NULL;
ALTER TABLE `users` ADD `description` varchar(255) NULL;
ALTER TABLE `users` ADD `fail_count` INT DEFAULT 0 NOT NULL;
ALTER TABLE `users` ADD `phone` varchar(16) NULL;
ALTER TABLE `users` ADD `tg` varchar(255) NULL;
ALTER TABLE `users` ADD `hide_contacts` BOOL DEFAULT 0 NOT NULL;
ALTER TABLE `users` ADD `is_contractor` BOOL DEFAULT 0 NOT NULL;

-- ----------------------------
-- Table structure for user_categories
-- ----------------------------
DROP TABLE IF EXISTS `user_categories`;
CREATE TABLE `user_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_categories_users_1` (`user_id`),
  KEY `fk_user_categories_categories_1` (`category_id`),
  CONSTRAINT `fk_user_categories_categories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_user_categories_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_settings
-- ----------------------------
DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE `user_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) DEFAULT NULL,
  `bd` date DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `about` text,
  `phone` char(11) DEFAULT NULL,
  `skype` char(32) DEFAULT NULL,
  `messenger` char(32) DEFAULT NULL,
  `notify_new_msg` tinyint(1) unsigned DEFAULT '0',
  `notify_new_action` tinyint(1) unsigned DEFAULT '0',
  `notify_new_reply` tinyint(1) unsigned DEFAULT '0',
  `opt_hide_contacts` tinyint(1) unsigned DEFAULT '0',
  `opt_hide_me` tinyint(1) unsigned DEFAULT '0',
  `is_performer` tinyint(1) unsigned DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_user_settings_users_1` (`user_id`) USING BTREE,
  UNIQUE KEY `phone` (`phone`,`skype`,`messenger`),
  CONSTRAINT `fk_user_settings_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_clients` FOREIGN KEY (client_id) REFERENCES `users`(id);
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_performers` FOREIGN KEY (performer_id) REFERENCES `users`(id);

SET FOREIGN_KEY_CHECKS=1;
