<?php
class LayoutsController extends AppController {
    var $name = 'Layouts';
    // This controller doesn't use any datasource.
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('menu');
    }

    /**
     * Gets the current menu.
     */
    function menu() {
        $userId = $this->Auth->user('id');
        if ($userId != null) {
            $this->set('userId', $userId);
        }
        if ($this->Session->check('id')) {
            $username = 'N/A';
            if ($this->Session->check('Auth.User.nickname')) {
                $username = $this->Session->read('Auth.User.nickname');
            } else if ($this->Session->check('Auth.User.username')) {
                $username = $this->Session->read('Auth.User.username');
            } else if ($this->Session->check('Auth.User.first_name')) {
                $username = $this->Session->read('Auth.User.first_name');
            }
            echo $username;
            $this->set('username', $username);
        }
        $this->layout = null;
    }

}

?>