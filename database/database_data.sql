-- Make sure you are using the correct database.
LOAD DATA LOCAL INFILE 'locations_data.txt' INTO TABLE `locations`(`code`, `name`);
LOAD DATA LOCAL INFILE 'categories_data.txt' INTO TABLE `categories`(`id`, `name`);
LOAD DATA LOCAL INFILE 'groups_data.txt' INTO TABLE `groups`(`id`, `name`);

