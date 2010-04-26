<?php
class Tag extends AppModel {
    var $name = 'Tag';
    var $hasAndBelongsToMany = array(
            'Item' => array(
                        'className' => 'Item',
                        'joinTable' => 'items_tags',
                        'foreignKey' => 'tag_id',
                        'associationForeignKey' => 'item_id'));
}
?>