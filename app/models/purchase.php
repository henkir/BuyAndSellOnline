<?php
class Purchase extends AppModel {
    var $name = 'Purchase';
    var $belongsTo = array('User', 'Item');
    
}
?>