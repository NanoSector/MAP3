-- Adminer 3.6.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `m3_boards`;
CREATE TABLE `m3_boards` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_topics` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `board_order` int(11) NOT NULL,
  `permissions` text NOT NULL,
  `last_post` int(11) NOT NULL,
  PRIMARY KEY (`board_id`),
  UNIQUE KEY `board_id` (`board_id`,`board_order`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `m3_categories`;
CREATE TABLE `m3_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `cat_order` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `m3_error_log`;
CREATE TABLE `m3_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` mediumtext NOT NULL,
  `file` mediumtext NOT NULL,
  `line` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `m3_log_online`;
CREATE TABLE `m3_log_online` (
  `session` longtext NOT NULL,
  `userid` mediumtext NOT NULL,
  `passhash` longtext NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `m3_messages`;
CREATE TABLE `m3_messages` (
  `subject` text NOT NULL,
  `id_msg` int(11) NOT NULL AUTO_INCREMENT,
  `id_topic` int(11) NOT NULL,
  `id_board` int(11) NOT NULL,
  `time_posted` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `member_name` text NOT NULL,
  `member_email` text NOT NULL,
  `time_updated` int(11) NOT NULL,
  `updated_by` text NOT NULL,
  `body` text NOT NULL,
  `is_topic` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id_msg`),
  UNIQUE KEY `id_msg` (`id_msg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `m3_permissions`;
CREATE TABLE `m3_permissions` (
  `id_permission` int(11) NOT NULL,
  `id_membergroup` mediumtext NOT NULL,
  `permission_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `m3_permissions` (`id_permission`, `id_membergroup`, `permission_name`) VALUES
(1,	'1',	'admin_forum');

DROP TABLE IF EXISTS `m3_settings`;
CREATE TABLE `m3_settings` (
  `setting` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `m3_settings` (`setting`, `value`) VALUES
('forum_name',	'Test Community'),
('ctheme',	'default'),
('m3_rkey',	'dujmocsuzed'),
('news',	'A random news line which should be changed in the Admin Panel.'),
('ie_warning',	'1'),
('time_format',	'd-m-Y H:i:s'),
('display_errors',	'1'),
('frontpage_enabled',	'1'),
('frontpage_text',	'Test'),
('smiley_pack',	'default');

DROP TABLE IF EXISTS `m3_users`;
CREATE TABLE `m3_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `displayname` text NOT NULL,
  `password_hash` text NOT NULL,
  `date_registered` text NOT NULL,
  `email` text NOT NULL,
  `avatar` text NOT NULL,
  `membergroup` int(11) NOT NULL,
  `email_me` int(11) NOT NULL,
  `email_news` int(11) NOT NULL,
  `website_url` mediumtext NOT NULL,
  `website_title` mediumtext NOT NULL,
  `birthdate` tinytext NOT NULL,
  `gender` int(11) NOT NULL,
  `location` mediumtext NOT NULL,
  `signature` longtext NOT NULL,
  `timeoffset` tinytext NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2013-03-03 01:51:33