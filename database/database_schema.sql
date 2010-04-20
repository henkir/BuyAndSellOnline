-- Drop and recreate the database.
DROP DATABASE IF EXISTS baso;
CREATE DATABASE baso;
-- Use the database.
USE baso;
-- Create groups, such as members, administrators, etc.
CREATE TABLE `groups` ( `id` INT(1) UNSIGNED NOT NULL,
                        `name` VARCHAR(20) NOT NULL UNIQUE,
                        PRIMARY KEY(`id`)
                        ) ENGINE=InnoDB;
-- Create locations, such as countries.
CREATE TABLE `locations` (  `id` INT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `code` CHAR(2) NOT NULL UNIQUE,
                            `name` VARCHAR(50) NOT NULL,
                            PRIMARY KEY(`id`)
                            ) ENGINE=InnoDB;
-- Create users, with an id, belonging to a group and living in a location.
CREATE TABLE `users` (  `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `group_id` INT(1) UNSIGNED DEFAULT 0,
                        `location_id` INT(2) UNSIGNED DEFAULT NULL,
                        PRIMARY KEY(`id`),
                        CONSTRAINT `user_group_fk`
                        FOREIGN KEY(`group_id`) REFERENCES `groups`(`id`)
                        ON UPDATE CASCADE ON DELETE RESTRICT
                        ) ENGINE=InnoDB;
-- Create default allowed locations, that the user sets.
CREATE TABLE `allowed_default` (    `user_id` INT(4) UNSIGNED NOT NULL,
                                    `location_id` INT(4) UNSIGNED NOT NULL,
                                    PRIMARY KEY(`user_id`, `location_id`),
                                    CONSTRAINT `allowed_default_user_fk`
                                    FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
                                    ON UPDATE CASCADE ON DELETE CASCADE
                                    ) ENGINE=InnoDB;
-- Create categories, that items can belong to.
CREATE TABLE `categories` ( `id` INT(2) UNSIGNED NOT NULL,
                            `name` VARCHAR(20) NOT NULL UNIQUE,
                            PRIMARY KEY(`id`)
                            ) ENGINE=InnoDB;
-- Create items, that has information about items a user added.
CREATE TABLE `items` (  `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `user_id` INT(4) UNSIGNED NOT NULL,
                        `name` VARCHAR(20) NOT NULL,
                        `description` TEXT NOT NULL,
                        `category_id` INT(2) UNSIGNED NOT NULL,
                        `price` DECIMAL(6,2) UNSIGNED NOT NULL,
                        `sold` BOOLEAN NOT NULL DEFAULT FALSE,
                        `paypal` VARCHAR(100) NOT NULL,
                        `picture` BLOB DEFAULT NULL,
                        PRIMARY KEY(`id`),
                        CONSTRAINT `items_user_fk`
                        FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
                        ON UPDATE CASCADE ON DELETE CASCADE,
                        CONSTRAINT `items_category_fk`
                        FOREIGN KEY(`category_id`) REFERENCES `categories`(`id`)
                        ON UPDATE CASCADE ON DELETE RESTRICT
                        ) ENGINE=InnoDB;
-- Create tags, that describe an item.
CREATE TABLE `tags` (   `item_id` INT(8) UNSIGNED NOT NULL,
                        `tag_name` VARCHAR(20) NOT NULL,
                        PRIMARY KEY(`item_id`, `tag_name`),
                        CONSTRAINT `tags_item_fk`
                        FOREIGN KEY(`item_id`) REFERENCES `items`(`id`)
                        ON UPDATE CASCADE ON DELETE CASCADE
                        ) ENGINE=InnoDB;
-- Create allowed locations, which specify which locations a user is allowed to
-- live in to purchase an item.
CREATE TABLE `allowed` (    `item_id` INT(8) UNSIGNED NOT NULL,
                            `location_id` INT(2) UNSIGNED NOT NULL,
                            PRIMARY KEY(`item_id`, `location_id`),
                            CONSTRAINT `allowed_item_fk`
                            FOREIGN KEY(`item_id`) REFERENCES `items`(`id`)
                            ON UPDATE CASCADE ON DELETE CASCADE,
                            CONSTRAINT `allowed_location_fk`
                            FOREIGN KEY(`location_id`) REFERENCES `locations`(`id`)
                            ON UPDATE CASCADE ON DELETE CASCADE
                            ) ENGINE=InnoDB;
-- Create purchases, that keep track of all purchases and when confirmed is set
-- to true, the payment should go to the seller.
CREATE TABLE `purchases` (  `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
                            `user_id` INT(4) UNSIGNED,
                            `item_id` INT(8) UNSIGNED,
                            `confirmed` BOOLEAN NOT NULL DEFAULT FALSE,
                            PRIMARY KEY(`id`),
                            CONSTRAINT `purchases_user_fk`
                            FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
                            ON UPDATE CASCADE ON DELETE SET NULL,
                            CONSTRAINT `purchases_item_fk`
                            FOREIGN KEY(`item_id`) REFERENCES `items`(`id`)
                            ON UPDATE CASCADE ON DELETE SET NULL
                            ) ENGINE=InnoDB;
