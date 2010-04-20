<?php
class Purchase extends AppModel {
    var $name = 'Purchase';
    var $hasOne = array('User', 'Item');
    
}
?>