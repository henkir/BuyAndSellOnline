<?php
class UsersController extends AppController {

    var $name = 'Users';
    // The IP address of the server. Needed since localhost isn't gonna do us
    // much good when using OpenID.
    var $ip = '94.254.42.77';

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

    function add($data) {
	$this->data['User'] = $data;
	$this->User->save($this->data);
	$this->Session->write('User.id', $this->User->getLastInsertID());
	return $this->User->getLastInsertID();
    }

    function delete($id) {
	$this->User->delete($id);
	$this->Session->setFlash('The user has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

    // Logs in a user with OpenID.
    function login() {
    	$returnTo = 'http://'.$this->ip.Configure::read('relativeUrl').'/users/login';

	// If data isn't empty, try to authenticate user using OpenID.
        if (!empty($this->data)) {
            try {
                $this->Openid->authenticate($this->data['OpenidUrl']['openid'], $returnTo, 'http://'.$this->ip.Configure::read('relativeUrl'));
            } catch (InvalidArgumentException $e) {
                $this->setMessage('Invalid OpenID');
            } catch (Exception $e) {
                $this->setMessage($e->getMessage());
            }
        } elseif (count($_GET) > 1) {
	    // Response from OpenID
            $response = $this->Openid->getResponse($returnTo);
	    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
	    $sreg = $sregResponse->contents();
	    print_r($sregResponse);
	    $sreg['openid'] = $_GET['openid_identity'];
	    $this->testUser($sreg);
	    $this->Session->setFlash('Successfully authenticated!');
	    $this->redirect('/');
	    $this->autoRender = false;
        }
    } 

    // Test if the user has logged in before and log in the user.
    function testUser($data) {
	//$data['name'] = $data['fullname'];
	$test = $this->User->findByOpenid($data['openid']);
	$return['action'] = 'index';

	if ($test) {
	    // User has logged in before, recreate data.
	    $data = array_merge($test['User'], $data);
	    $this->recreate($data);
	    $return['id'] = $test['User']['id'];
	} else {
	    // First time, add user.
	    $return['id'] = $this->add($data);
	    $return['action'] = 'index';
	}
	// Finally log in the user.
	$this->Auth->login($return['id']);
	//return $return;
    }

    // Recreate user given data.
    function recreate($data) {
	$this->data['User'] = $data;
	$this->User->save($this->data['User']);
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