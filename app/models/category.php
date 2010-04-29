<?php
class Category extends AppModel {
    var $name = 'Category';
    var $hasMany = array('Category' => array('finderQuery' => 'SELECT `Category`.`id`, `Category`.`name`, `Category`.`category_id` FROM `categories` AS `Category` LEFT JOIN `categories` AS `Category2` ON (`Category`.`category_id` = `Category`.`id`)  WHERE 1 = 1'));

}
?>