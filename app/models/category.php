<?php
class Category extends AppModel {
    var $name = 'Category';
    /**
     * A Category has many Items and may also have many Categories.
     * Required to have a custom finder query to find child categories.
     */
    var $hasMany =
	array('Item',
	      'Category' => array('finderQuery' =>
				  'SELECT `Category`.`id`, `Category`.`name`, `Category`.`category_id` FROM `categories` AS `Category` LEFT JOIN `categories` AS `Category2` ON (`Category`.`category_id` = `Category`.`id`)  WHERE 1 = 1'));

    /**
     * Validates the Category model.
     * Name cannot be empty, cannot be longer than 20 characters and must be
     * alpha-numeric.
     */
    var $validate = array('name' => array('nameRule1' => array('rule' => 'notEmpty',
							       'required' => true,
							       'last' => true,
							       'message' => 'Category name cannot be empty.'),
					  'nameRule2' => array('rule' => array('maxLength', 20),
							       'last' => true,
							       'message' => 'Category name is too long.'),
					  'nameRule3' => array('rule' => 'alphaNumeric',
							       'message' => 'Category name contains invalid characters.')));

}
?>