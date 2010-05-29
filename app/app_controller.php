<?php

class AppController extends Controller {

    // We load the most common components here so we won't have to do it in
    // all controllers.
    var $components = array('RequestHandler', 'Acl', 'Auth', 'Session');
    // We will use these helpers in almost all views.
    var $helpers = array('Html', 'Javascript', 'Ajax',
                   'Form', 'Time', 'Text', 'Number');

    /**
     * Checks if the request was an AJAX request, if so we want no debug prints.
     */
    function _checkAjax() {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
            return true;
        } else {
            return false;
        }
    }

    function beforeFilter() {
        parent::beforeFilter();
        Configure::write('debug', 2);
        $this->_configure();
        $this->_checkAjax();
        $this->_setUpAuth();
        $this->_setPrivileges();
        App::import('Vendor', 'Facebook');
        $this->set('title', 'BuyAndSellOnline');

    }

    function _configure() {
        // Set the relative URL from the web root directory. Empty if root.
        // Set the IP of the server. Needed for OpenID.
        Configure::write('ip', '94.254.42.77');
        Configure::write('itemsPerPage', 8);
        Configure::write('usersPerPage', 8);
        Configure::write('tagsPerPage', 20);
        Configure::write('categoriesPerPage', 20);
    }

    /**
     * Checks what privileges the User has and sets the variables
     * loggedIn, moderator and admin to true or false.
     */
    function _setPrivileges() {
        $id = $this->Auth->user('id');
        $groupId = $this->Auth->user('group_id');
        $loggedIn = false;
        $moderator = false;
        $admin = false;
        if (!empty($id)) {
            $loggedIn = true;
            if ($groupId >= 2) {
                $moderator = true;
                if ($groupId >= 3) {
                    $admin = true;
                }
            }
        }
        if ($id != null) {
            $this->set('userId', $id);
        }
        $this->set('loggedIn', $loggedIn);
        $this->set('moderator', $moderator);
        $this->set('admin', $admin);
    }

    /**
     * Sets up the Auth component.
     */
    function _setUpAuth() {
        $this->Auth->authorize = 'actions';
        $this->Auth->actionPath = 'controllers/';
        $this->Auth->autoRedirect = true;
        $this->Auth->userScope = array('User.banned' => 0);
        $this->Auth->loginAction = array(
            'controller' => 'users',
            'action' => 'login');
        $this->Auth->logoutRedirect = '/';
        $this->Auth->loginRedirect = '/';
        // Always allow display (view that is rendered in the start page).
        $this->Auth->allowedActions = array('display');
        //$this->Auth->allow('*');
    }


  }
?>