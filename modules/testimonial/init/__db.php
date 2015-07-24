<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$db_install['table_name'] = 'testimonial';
$db_install['query']['testimonial'] = "CREATE TABLE IF NOT EXISTS `testimonial` (
						  `testimonial_id` int(11) NOT NULL AUTO_INCREMENT,
						  `testimonial_user_id` int(11) DEFAULT '0',
						  `testimonial_name` varchar(255) DEFAULT NULL,
						  `testimonial_email` varchar(255) DEFAULT NULL,
						  `testimonial_phone` varchar(50) DEFAULT NULL,
						  `testimonial_website` varchar(255) DEFAULT NULL,
						  `testimonial_location` varchar(255) DEFAULT NULL,
						  `testimonial_text` text,
						  `testimonial_image` varchar(255) DEFAULT NULL,
						  `testimonial_created` datetime DEFAULT '0000-00-00 00:00:00',
						  `testimonial_active` tinyint(2) DEFAULT '0',
						 PRIMARY KEY (`testimonial_id`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$db_install['query']['testimonial_settings'] = "CREATE TABLE IF NOT EXISTS `testimonial_settings` (
						  `id` int(5) DEFAULT NULL,
						  `show_phone` tinyint(2) DEFAULT '0',
						  `show_website` tinyint(2) DEFAULT '0',
						  `show_location` tinyint(2) DEFAULT '0',
						  `show_images` tinyint(2) DEFAULT '0',
						  `show_captcha` tinyint(2) DEFAULT '0',
						  `required_name` tinyint(2) DEFAULT '1',
						  `required_email` tinyint(2) DEFAULT '1',
						  `required_phone` tinyint(2) DEFAULT '0',
						  `required_website` tinyint(2) DEFAULT '0',
						  `required_location` tinyint(2) DEFAULT '0',
						  `required_images` tinyint(2) DEFAULT '0',
						  `error_messages` varchar(255) DEFAULT NULL,
						  `success_messages` varchar(255) DEFAULT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";