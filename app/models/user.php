<?php
class User extends AppModel {
    var $name = 'User';
    var $belongsTo = array('Group');
    var $hasMany = array('Item');
}
?>