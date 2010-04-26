<?php
class Group extends AppModel {

	var $name = 'Group';
    var $actsAs = array('Acl' => 'requester');

	/*var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'group_id',
			'dependent' => false));*/

    function parentNode() {
        return null;
    }

}
?>