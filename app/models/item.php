<?php
class Item extends AppModel {
    var $name = 'Item';
    var $belongsTo = array('User', 'Category');
    var $hasMany = array('Tag' => array(
                                    'className' => 'Tag',
                                    'foreignKey' => 'item_id',
                                    'dependent' => true));
}
?>