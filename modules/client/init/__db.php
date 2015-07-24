<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$db_install['query']['client'] = "CREATE TABLE `client` (
				  `client_id` int(11) NOT NULL AUTO_INCREMENT,
				  `client_name` varchar(255) NOT NULL,
				  `client_website` varchar(255) NOT NULL,
				  `client_image` varchar(255) NOT NULL,
				  `client_description` varchar(255) NOT NULL,
				  `client_active` tinyint(2) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`client_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";