<?php
class Group extends AppModel {
    var $name = 'Group';
    var $hasMany = array('User');
    
}
?>