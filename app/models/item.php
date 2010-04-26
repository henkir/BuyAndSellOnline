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
}
?>