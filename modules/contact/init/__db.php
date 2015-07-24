<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$db_install['query']['testimonial_settings'] = "CREATE TABLE `contact_settings` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) DEFAULT NULL,
					  `email` varchar(255) DEFAULT NULL,
					  `phone` varchar(255) DEFAULT NULL,
					  `phone_2` varchar(255) DEFAULT NULL,
					  `fax` varchar(255) DEFAULT NULL,
					  `address` varchar(255) DEFAULT NULL,
					  `latitude` varchar(255) DEFAULT NULL,
					  `longitude` varchar(255) DEFAULT NULL,
					  `work_hours` varchar(255) DEFAULT NULL,
					  `show_name` tinyint(2) DEFAULT '1',
					  `show_email` tinyint(2) DEFAULT '1',
					  `show_address` tinyint(2) DEFAULT '1',
					  `show_phone` tinyint(2) DEFAULT '1',
					  `show_subject` tinyint(2) DEFAULT '1',
					  `show_message` tinyint(2) DEFAULT '1',
					  `show_captcha` tinyint(2) DEFAULT '1',
					  `required_address` tinyint(2) DEFAULT '0',
					  `required_phone` tinyint(2) DEFAULT '0',
					  `required_subject` tinyint(2) DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";