-- Adminer 3.6.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `m3_boards` (`board_id`, `name`, `description`, `num_posts`, `num_topics`, `category_id`, `board_order`, `permissions`, `last_post`) VALUES
(1,	'A Sample Board',	'Lets see if it gets sorted well.',	1,	1,	1,	1,	'',	1),
(2,	'Another board',	'Maybe this doesn\'t get sorted well?',	0,	0,	1,	3,	'',	0),
(3,	'Yet another board.',	'Another test. Now with permissions!',	0,	0,	1,	2,	'-1',	0),
(4,	'A board in the second category.',	'Do I need to explain?',	0,	0,	2,	5,	'',	0),
(5,	'Another board in the second category.',	'I don\'t want to explain :(',	0,	0,	2,	4,	'',	0);

INSERT INTO `m3_categories` (`cat_id`, `name`, `cat_order`) VALUES
(1,	'A Sample Category',	0),
(2,	'Another Category',	0);

INSERT INTO `m3_messages` (`subject`, `id_msg`, `id_topic`, `id_board`, `time_posted`, `member`, `member_name`, `member_email`, `time_updated`, `updated_by`, `body`, `is_topic`, `views`) VALUES
('Test topic',	1,	1,	1,	1361713652,	1,	'Yoshi2889',	'rick.2889@gmail.com',	0,	'',	'A test topic.',	1,	0);

-- 2013-03-03 01:52:20