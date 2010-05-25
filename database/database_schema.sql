-- Drop and recreate the database.
DROP DATABASE IF EXISTS baso;
CREATE DATABASE baso;
-- Use the database.
USE baso;
-- Create groups, such as members, administrators, etc.
CREATE TABLE `groups` ( `id` INT(1) NOT NULL,
                        `name` VARCHAR(20) NOT NULL UNIQUE,
                        PRIMARY KEY(`id`)
                        ) ENGINE=MyISAM;
CREATE TABLE `countries` (	`id` CHAR(2) NOT NULL,
       	     		 	`name` VARCHAR(50) NOT NULL,
				PRIMARY KEY(`id`)
				) ENGINE=MyISAM;
-- Create users, mostly holding OpenID and group.
CREATE TABLE `users` (  `id` integer auto_increment,
                        `username` varchar(20),
                        `password` char(40),
			`email` varchar(50),
			`openid` char(80),
			`facebookid` char(20),
			`nickname` varchar(20),
			`first_name` varchar(20),
			`last_name` varchar(20),
			`address` varchar(30),
			`country_id` char(2),
			`city` varchar(20),
			`zip` char(8),
                        `group_id` INT(1) DEFAULT 1,
			`created` DATETIME,
			`modified` DATETIME,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM;
-- Create categories, that items can belong to.
CREATE TABLE `categories` ( `id` INT(2) NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(20) NOT NULL UNIQUE,
			    `category_id` INT(2) DEFAULT NULL,
                            PRIMARY KEY(`id`)
                            ) ENGINE=MyISAM;
-- Create items, that has information about items a user added.
CREATE TABLE `items` (  `id` INTEGER NOT NULL AUTO_INCREMENT,
                        `user_id` INTEGER NOT NULL,
                        `name` VARCHAR(20) NOT NULL,
                        `description` TEXT NOT NULL,
                        `category_id` INT(2) NOT NULL,
                        `price` DECIMAL(6,2) UNSIGNED NOT NULL,
                        `sold` BOOLEAN NOT NULL DEFAULT FALSE,
                        `paypal` VARCHAR(100) NOT NULL,
                        `image` CHAR(36) DEFAULT NULL,
			`created` DATETIME NOT NULL,
			`modified` DATETIME,
			`agreed` BOOLEAN NOT NULL DEFAULT 0,
                        PRIMARY KEY(`id`)
                        ) ENGINE=MyISAM;
-- Create tags, that describe an item.
CREATE TABLE `tags` (   `id` INTEGER NOT NULL AUTO_INCREMENT,
                        `name` VARCHAR(20) NOT NULL,
                        PRIMARY KEY(`id`)
                        ) ENGINE=MyISAM;
-- Create table for connections between items and tags.
CREATE TABLE `items_tags` ( `id` INTEGER NOT NULL AUTO_INCREMENT,
                            `item_id` INTEGER NOT NULL,
                            `tag_id` INTEGER NOT NULL,
                            PRIMARY KEY(`id`)
                        ) ENGINE=MyISAM;
-- Create purchases, that keep track of all purchases and when confirmed is set
-- to true, the payment should go to the seller.
CREATE TABLE `purchases` (  `id` INTEGER NOT NULL AUTO_INCREMENT,
                            `user_id` INTEGER,
                            `item_id` INTEGER,
                            `confirmed` BOOLEAN NOT NULL DEFAULT FALSE,
                            PRIMARY KEY(`id`)
                            ) ENGINE=MyISAM;
-- Create ACL tables
SOURCE db_acl.sql;

