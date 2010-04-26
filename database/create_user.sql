-- Creates a user for CakePHP to use, must be used from localhost unless altered.
CREATE USER 'baso_user'@'localhost';
SET PASSWORD FOR 'baso_user'@'localhost' = PASSWORD('basoPaSsw0rd135');
GRANT SELECT, UPDATE, INSERT, DELETE ON baso.* TO 'baso_user'@'localhost';

