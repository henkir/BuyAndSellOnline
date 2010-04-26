<?php
class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('Auth', 'Acl');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowActions = array('index');
    }

    function index() {
        $this->set('users', $this->User->find('all'));
    }

    function view($id = null) {
        $this->User->id = $id;
        $this->set('user', $this->User->read());
    }
    
    function login() {

    }
    
    function logout() {
        $this->Session->setFlash('Logged out');
        $this->redirect($this->Auth->logout());
    }

    // Set privileges for different groups, should be made private in production
    function initDB() {
        $group =& $this->User->Group;
        // Administrators
        $group->id = 3;
        $this->Acl->allow($group, 'controllers');
        // Moderators
        $group->id = 2;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'Items');
        $this->Acl->allow($group, 'Tags');
        $this->Acl->allow($group, 'Categories');
        $this->Acl->allow($group, 'Purchases');
        $this->Acl->allow($group, 'Groups');
        $this->Acl->allow($group, 'Users');
        // Members
        $group->id = 1;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'Items');
        $this->Acl->allow($group, 'Tags');
        $this->Acl->allow($group, 'Categories');
        $this->Acl->allow($group, 'Purchases');
        $this->Acl->allow($group, 'Groups');
        $this->Acl->allow($group, 'Users');
    }

}
?>