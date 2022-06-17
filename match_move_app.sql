#database name
CREATE DATABASE `my_proj`;

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


CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password` varchar(256) DEFAULT '',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `max_requests_per_minute` int(11)  DEFAULT 100,
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `contact_number` (`contact_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='List of all system users';


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


#create one entry for the sys_config and user as admin table ..