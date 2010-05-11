<?php
class Tag extends AppModel {
    var $name = 'Tag';
    /**
     * A Tag has and belongs to many Items.
     */
    var $hasAndBelongsToMany = array(
            'Item' => array(
                        'className' => 'Item',
                        'joinTable' => 'items_tags',
                        'foreignKey' => 'tag_id',
                        'associationForeignKey' => 'item_id'));
}
?>