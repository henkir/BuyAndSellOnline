<?php
class Item extends AppModel {
    var $name = 'Item';
    /**
     * An Item can have at most one Purchase.
     */
    var $hasOne = array('Purchase');
    /*
     * An Item belongs to a User and a Category.
     */
    var $belongsTo = array('User', 'Category');
    /**
     * An Item has and belongs to many Tags.
     */
    var $hasAndBelongsToMany = array(
            'Tag' => array(
                        'className' => 'Tag',
                        'joinTable' => 'items_tags',
                        'foreignKey' => 'item_id',
                        'associationForeignKey' => 'tag_id'));
    /**
     * Validation rules for the model.
     * Name (or title) cannot be empty, cannot be longer than 20 characters and must be alpha-numeric.
     * Description cannot be empty and cannot be longer than 2500 characters.
     * Paypal must be a valid email and have a maximum length of 100 characters.
     * Price must be a valid monetary value.
     * Agreed must be true.
     */
    var $validate = array('name' => array('nameRule1' => array('rule' => 'notEmpty',
                                                               'required' => true,
                                                               'last' => true,
                                                               'message' => 'The title cannot be left blank.'),
                                          'nameRule2' => array('rule' => array('maxLength', 20),
                                                               'last' => true,
                                                               'message' => 'The title is too long.'),
                                          'nameRule3' => array('rule' => 'alphaNumeric',
                                                               'message' => 'The title may only contain alpha-numeric characters.')),
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