<?php
class Item extends AppModel {
    var $name = 'Item';
    var $hasOne = array('Purchase');
    var $belongsTo = array('User', 'Category');
    var $hasAndBelongsToMany = array(
            'Tag' => array(
                        'className' => 'Tag',
                        'joinTable' => 'items_tags',
                        'foreignKey' => 'item_id',
                        'associationForeignKey' => 'tag_id'));

    var $validate = array('name' => array('nameRule1' => array('rule' => 'notEmpty',
							       'required' => true,
							       'last' => true,
							       'message' => 'The title cannot be left blank.'),
					  'nameRule2' => array('rule' => array('maxLength', 20),
							       'message' => 'The title is too long.')),
			  'description' => array('descRule1' => array('rule' => 'notEmpty',
								      'required' => true,
								      'last' => true,
								      'message' => 'The description cannot be left blank.'),
						 'descRule2' => array('rule' => array('maxLength', 2500),
								      'message' => 'The description is too long.')),
			  'paypal' => array('paypalRule1' => array('rule' => 'email',
								   'required' => true,
								   'last' => true,
								   'message' => 'A valid email address is required.'),
					    'paypalRule2' => array('rule' => array('maxLength', 100),
								   'required' => true,
								   'message' => 'The email address is too long.')),
			  'price' => array('rule' => 'money',
					   'required' => true,
					   'message' => 'The entered price is not valid.'),
			  'agreed' => array('rule' => array('comparison', '!=', 0),
					    'required' => true,
					    'message' => 'You must agree to the terms of use.'));

}
?>