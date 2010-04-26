<?php
class GroupsController extends AppController {
	var $name = 'Groups';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('view');
    }

}

?>