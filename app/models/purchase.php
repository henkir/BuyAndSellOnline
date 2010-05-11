<?php
class Purchase extends AppModel {
    var $name = 'Purchase';
    /**
     * A Purchase belongs to a User and an Item.
     */
    var $belongsTo = array('User', 'Item');

}
?>