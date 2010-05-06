-- Drop and recreate the database.
DROP DATABASE IF EXISTS baso;
CREATE DATABASE baso;
-- Use the database.
USE baso;
-- Create groups, such as members, administrators, etc.
CREATE TABLE `groups` ( `id` INT(1) NOT NULL,
                        `name` VARCHAR(20) NOT NULL UNIQUE,
                        PRIMARY KEY(`id`)
                        ) ENGINE=InnoDB;
-- Create locations, such as countries.
-- CREATE TABLE `locations` (  `id` INT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
--                            `code` CHAR(2) NOT NULL UNIQUE,
--                            `name` VARCHAR(50) NOT NULL,
--                            PRIMARY KEY(`id`)
--                            ) ENGINE=InnoDB;
CREATE TABLE `users` (  `id` integer auto_increment,
                        `username` char(50),
                        `password` char(40),
			`openid` char(80),
                        `name` varchar(32),
                        `last_name` varchar(32),
                        `group_id` INT(1) DEFAULT 0,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB;
-- Create users, with an id, belonging to a group and living in a location.
-- CREATE TABLE `users` (  `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
--                        `group_id` INT(1) UNSIGNED DEFAULT 0,
--                        -- `location_id` INT(2) UNSIGNED DEFAULT NULL,
--                        PRIMARY KEY(`id`),
--                        CONSTRAINT `user_group_fk`
--                        FOREIGN KEY(`group_id`) REFERENCES `groups`(`id`)
--                        ON UPDATE CASCADE ON DELETE RESTRICT
--                        ) ENGINE=InnoDB;
-- Create default allowed locations, that the user sets.
-- CREATE TABLE `users_locations` (    `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
--                                    `user_id` INT(4) UNSIGNED NOT NULL,
--                                    `location_id` INT(4) UNSIGNED NOT NULL,
--                                    PRIMARY KEY(`id`),
--                                    CONSTRAINT `allowed_default_user_fk`
--                                    FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
--                                    ON UPDATE CASCADE ON DELETE CASCADE
 --                                   ) ENGINE=InnoDB;
-- Create categories, that items can belong to.
CREATE TABLE `categories` ( `id` INT(2) NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(20) NOT NULL UNIQUE,
			    `category_id` INT(2) DEFAULT NULL,
                            PRIMARY KEY(`id`)
                            ) ENGINE=InnoDB;
-- Create items, that has information about items a user added.
CREATE TABLE `items` (  `id` INTEGER NOT NULL AUTO_INCREMENT,
                        `user_id` INTEGER NOT NULL,
                        `name` VARCHAR(20) NOT NULL,
                        `description` TEXT NOT NULL,
                        `category_id` INT(2) NOT NULL,
                        `price` DECIMAL(6,2) UNSIGNED NOT NULL,
                        `sold` BOOLEAN NOT NULL DEFAULT FALSE,
                        `paypal` VARCHAR(100) NOT NULL,
                        `picture` BLOB DEFAULT NULL,
			`created` DATETIME NOT NULL,
			`modified` DATETIME,
                        PRIMARY KEY(`id`)
                        ) ENGINE=InnoDB;
-- Create tags, that describe an item.
CREATE TABLE `tags` (   `id` INTEGER NOT NULL AUTO_INCREMENT,
                        `name` VARCHAR(20) NOT NULL,
                        PRIMARY KEY(`id`)
                        ) ENGINE=InnoDB;

CREATE TABLE `items_tags` ( `id` INTEGER NOT NULL AUTO_INCREMENT,
                            `item_id` INTEGER NOT NULL,
                            `tag_id` INTEGER NOT NULL,
                            PRIMARY KEY(`id`)
                        ) ENGINE=InnoDB;
-- Create allowed locations, which specify which locations a user is allowed to
-- live in to purchase an item.
-- CREATE TABLE `items_locations` (    `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
--                                    `item_id` INT(8) UNSIGNED NOT NULL,
--                                    `location_id` INT(2) UNSIGNED NOT NULL,
--                                    PRIMARY KEY(`id`),
--                                    CONSTRAINT `allowed_item_fk`
--                                    FOREIGN KEY(`item_id`) REFERENCES `items`(`id`)
--                                    ON UPDATE CASCADE ON DELETE CASCADE,
--                                    CONSTRAINT `allowed_location_fk`
--                                    FOREIGN KEY(`location_id`) REFERENCES `locations`(`id`)
--                                    ON UPDATE CASCADE ON DELETE CASCADE
--                                    ) ENGINE=InnoDB;
-- Create purchases, that keep track of all purchases and when confirmed is set
-- to true, the payment should go to the seller.
CREATE TABLE `purchases` (  `id` INTEGER NOT NULL AUTO_INCREMENT,
                            `user_id` INTEGER,
                            `item_id` INTEGER,
                            `confirmed` BOOLEAN NOT NULL DEFAULT FALSE,
                            PRIMARY KEY(`id`)
                            ) ENGINE=InnoDB;
-- Create ACL tables
SOURCE db_acl.sql;

