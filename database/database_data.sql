-- Make sure you are using the correct database.
LOAD DATA LOCAL INFILE 'countries_data.txt' INTO TABLE `countries`(`id`, `name`);
LOAD DATA LOCAL INFILE 'categories_data.txt' INTO TABLE `categories`(`id`, `name`);
LOAD DATA LOCAL INFILE 'groups_data.txt' INTO TABLE `groups`(`id`, `name`);
-- LOAD DATA LOCAL INFILE 'users_data.txt' INTO TABLE `users`(`username`, `password`, `email`, `first_name`, `last_name`, `group_id`, `nickname`, `country_id`);
LOAD DATA LOCAL INFILE 'items_data.txt' INTO TABLE `items`(`user_id`, `name`, `description`, `category_id`, `price`, `paypal`, `created`);
LOAD DATA LOCAL INFILE 'tags_data.txt' INTO TABLE `tags`(`name`);
LOAD DATA LOCAL INFILE 'items_tags_data.txt' INTO TABLE `items_tags`(`item_id`, `tag_id`);
