#database name
CREATE DATABASE `my_proj`;

CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `password` varchar(256) DEFAULT '',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `max_no_of_rqts_per_hour` int(11)  DEFAULT 100,
  `no_of_attempts` int(11)  DEFAULT 0,
  `initial_rqst_datetime` timestamp NULL DEFAULT NULL ,
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `contact_no` (`contact_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='List of all system users';


CREATE TABLE `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `token` varchar(250) NOT NULL DEFAULT '',
  `expires_at` timestamp NOT NULL , 
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `token_key1` FOREIGN KEY (`user_id`) REFERENCES `sys_user` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='User Token Table';


CREATE TABLE `sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `config` json NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1  COMMENT='List of system config';


#default entry for the admin user

INSERT INTO `sys_user` (`id`, `email`, `name`, `contact_no`, `password`, `status`, `user_type`, `max_no_of_rqts_per_hour`, `no_of_attempts`, `initial_rqst_datetime`, `is_deleted`, `created_at`, `updated_at`)
VALUES
	(1, 'admin@demoapp.com', 'admin', '7897891234', 'eyJpdiI6IlBuT0lqQ1BSZUhlcVVHcThJSXpCa2c9PSIsInZhbHVlIjoiT2FFSXZFalZxM1k1SjJQOGZFaFBhUT09IiwibWFjIjoiMzZmODc1MTQzNWE5M2Y3MTdhNjMwZGM2NDQ2ZWZiZGU2OWUwM2I5OTA0ZGFiNjRjYWQwYjVhNjQ2MTAwNjgzOCJ9', 'Active', 'admin', 100, 0, NULL, 'False', '2022-06-18 14:53:06', '2022-06-18 14:53:26');


#default entry for the sys config 

INSERT INTO `sys_config` (`id`, `name`, `config`, `status`, `is_deleted`, `created_at`, `updated_at`)
VALUES
	(1, 'token_hash', '"qbTQB2F1Fzp"', 'Active', 'False', '2022-06-18 15:01:49', '2022-06-18 15:02:41');