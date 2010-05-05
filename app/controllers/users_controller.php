<?php
class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('Auth', 'Acl', 'Openid');

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
    
    function edit($id) {
	$this->set('users', $this->User->find('all'));
	if (!empty($this->data)) {
	    if ($this->User->save($this->data)) {
		$this->Session->setFlash('The user has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the user.');
	    }
	}
    }

    function add() {
	$this->set('users', $this->User->find('all'));
	if (!empty($this->data)) {
	    if ($this->User->save($this->data)) {
		$this->Session->setFlash('The user has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the user.');
	    }
	}
    }

    function delete($id) {
	$this->User->delete($id);
	$this->Session->setFlash('The user has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

    function login() {
    	$returnTo = 'http://'.'85.24.196.11'.'/BuyAndSellOnline/users/login';

        if (!empty($this->data)) {
            try {
                $this->Openid->authenticate($this->data['OpenidUrl']['openid'], $returnTo, 'http://'.'85.24.196.11'.'/BuyAndSellOnline');
            } catch (InvalidArgumentException $e) {
                $this->setMessage('Invalid OpenID');
            } catch (Exception $e) {
                $this->setMessage($e->getMessage());
            }
        } elseif (count($_GET) > 1) {
            $response = $this->Openid->getResponse($returnTo);

            if ($response->status == Auth_OpenID_CANCEL) {
                $this->setMessage('Verification cancelled');
            } elseif ($response->status == Auth_OpenID_FAILURE) {
                $this->setMessage('OpenID verification failed: '.$response->message);
            } elseif ($response->status == Auth_OpenID_SUCCESS) {
                echo 'successfully authenticated!';
                exit;
            }
        }
    }

    private function setMessage($message) {
        $this->set('message', $message);
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