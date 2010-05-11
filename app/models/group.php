<?php
class Group extends AppModel {

    var $name = 'Group';
    /**
     * A Group acts as a requester.
     */
    var $actsAs = array('Acl' => 'requester');
    /**
     * A Group has many Users.
     */
    var $hasMany = array('User');

    /**
     * Gets the parent node of the Group.
     */
    function parentNode() {
        return null;
    }

  }
?>