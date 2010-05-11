<?php

class AppController extends Controller {

    // We load the most common components here so we won't have to do it in all controllers.
    var $components = array('RequestHandler', 'Auth', 'Session');
    // We will use these helpers in almost all views.
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Form', 'Time', 'Text');

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
        // Set the relative URL from the web root directory. Empty if root.
        Configure::write('relativeUrl', '/BuyAndSellOnline');
	// Set the IP of the server. Needed for OpenID.
        Configure::write('ip', '94.254.42.77');
        $this->_checkAjax();
        // Set convenience variables for whether a user is logged, moderator or admin.
        if ($this->Session->check('Auth.User.id')) {
            $this->set('loggedIn', true);
	    // TODO: check higher privs
        } else {
            $this->set('loggedIn', false);
        }
        // Set up authorization
        $this->Auth->authorize = 'actions';
        $this->Auth->actionPath = 'controllers/';
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display');
        $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'display');
        // Always allow display (view that is rendered in the start page).
        $this->Auth->allowedActions = array('display');
        // TODO: remove this
        $this->Auth->allow('*');
    }


  }
?>