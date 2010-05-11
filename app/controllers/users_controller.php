<?php
class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('Openid', 'Acl');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowActions = array('index', 'view');
    }

    /**
     * Gets all Users.
     */
    function index() {
        $this->set('users', $this->User->find('all'));
    }

    /**
     * Gets the Users with the given id.
     *
     * @param id the id of the User
     */
    function view($id = null) {
        $this->User->id = $id;
        $this->set('user', $this->User->read());
    }

    /**
     * Edits the Users with the given id.
     *
     * @param id the id of the User
     */
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

    /**
     * Adds a Users with the given data.
     *
     * @param data the data of the User
     * @return the id of the new User
     */
    function add($data) {
        $this->data['User'] = $data;
        $this->User->save($this->data);
        $this->Session->write('User.id', $this->User->getLastInsertID());
        return $this->User->getLastInsertID();
    }

    /**
     * Deletes the Users with the given id.
     *
     * @param id the id of the User
     */
    function delete($id) {
        $this->User->delete($id);
        $this->Session->setFlash('The user has been deleted.');
        $this->redirect(array('action' => 'edit'));
    }

    /**
     * Registers a user the traditional way.
     */
    function register() {
	if (!empty($this->data)) {
	    $this->data['User']['password'] = $this->Auth->password($this->data['User']['passwd']);
	    $this->data['User']['group_id'] = 1;
	    if ($this->User->save($this->data)) {
		$this->Session->setFlash('Successfully registered.',
					 'default',
					 array('class' => 'success'));
		$this->render('login');
	    } else {

		$this->Session->setFlash('Failed to register.',
					 'default',
					 array('class' => 'error'));
	    }
	}
    }

    /**
     * Logs in a user with OpenID, or the traditional way.
     */
    function login() {
        $returnTo = 'http://'.Configure::read('ip').Configure::read('relativeUrl').'/users/login';

        // If data isn't empty, try to authenticate user using OpenID.
        if (!empty($this->data)) {
	    if (!empty($this->data['OpenidUrl']['openid'])) {
		try {
		    $this->Openid->authenticate($this->data['OpenidUrl']['openid'],
						$returnTo,
						'http://' . Configure::read('ip') . Configure::read('relativeUrl'));
		} catch (InvalidArgumentException $e) {
		    $this->Session->setFlash('Invalid OpenID',
					     'default',
					     array('class' => 'error'));
		} catch (Exception $e) {
		    $this->Session->setFlash($e->getMessage(),
					     'default',
					     array('class' => 'error'));
		}
	    }
        } elseif (count($_GET) > 1) {
            // Response from OpenID
            $response = $this->Openid->getResponse($returnTo);
            $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            $sreg = $sregResponse->contents();
            $sreg['openid'] = $_GET['openid_identity'];
	    // New user becomes a member
	    $sreg['group_id'] = 1;
            $this->_testUser($sreg);
            $this->Session->setFlash('Successfully authenticated!', 'default', array('class' => 'success'));

            $this->redirect('/');
            $this->autoRender = false;
        }
    }

    /**
     * Tests if the user has logged in before and log in the user.
     */
    function _testUser($data) {
        //$data['name'] = $data['fullname'];
        $test = $this->User->findByOpenid($data['openid']);
        $return['action'] = 'index';

        if ($test) {
            // User has logged in before, recreate data.
            $data = array_merge($test['User'], $data);
            $this->_recreate($data);
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
    function _recreate($data) {
        $this->data['User'] = $data;
        $this->User->save($this->data['User']);
    }

    /**
     * Logs out the User currently logged in.
     */
    function logout() {
        $this->Session->setFlash('Logged out', 'default', array('class' => 'success'));
        $this->redirect($this->Auth->logout());
    }

    /**
     * Sets privileges for different groups, should be made private in production
     */
    function initDB() {
        $group =& $this->User->Group;
        // Administrators
        $group->id = 2;
        $this->Acl->allow($group, 'controllers');
        // Moderators
        $group->id = 1;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'controllers/Items');
        $this->Acl->allow($group, 'controllers/Tags');
        $this->Acl->allow($group, 'controllers/Categories');
        $this->Acl->allow($group, 'controllers/Purchases');
        $this->Acl->allow($group, 'controllers/Groups');
        $this->Acl->allow($group, 'controllers/Users');
        // Members
        $group->id = 0;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'controllers/Items/add');
        $this->Acl->allow($group, 'controllers/Tags/add');
    }

    // Functions for building ACOs ---------------------------------------------------------------
    function build_acl() {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }
        $log = array();

        $aco =& $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }

        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false ) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            // Do all Plugins First
            if ($this->_isPlugin($ctrlName)){
                $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
                if (!$pluginNode) {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
                    $pluginNode = $aco->save();
                    $pluginNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
                }
            }
            // find / make controller node
            $controllerNode = $aco->node('controllers/'.$ctrlName);
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)){
                    $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                    $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
                } else {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $ctrlName;
                }
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for '. $method;
                }
            }
        }
        if(count($log)>0) {
            debug($log);
        }
    }

    function _getClassMethods($ctrlName = null) {
        App::import('Controller', $ctrlName);
        if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
            $num = strpos($ctrlName, '.');
            $ctrlName = substr($ctrlName, $num+1);
        }
        $ctrlclass = $ctrlName . 'Controller';
        $methods = get_class_methods($ctrlclass);

        // Add scaffold defaults if scaffolds are being used
        $properties = get_class_vars($ctrlclass);
        if (array_key_exists('scaffold',$properties)) {
            if($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
            } else {
                $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
            }
        }
        return $methods;
    }

    function _isPlugin($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) > 1) {
            return true;
        } else {
            return false;
        }
    }

    function _getPluginControllerPath($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0] . '.' . $arr[1];
        } else {
            return $arr[0];
        }
    }

    function _getPluginName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0];
        } else {
            return false;
        }
    }

    function _getPluginControllerName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[1];
        } else {
            return false;
        }
    }

    /**
     * Get the names of the plugin controllers ...
     *
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names.
     *
     */
    function _getPluginControllerNames() {
        App::import('Core', 'File', 'Folder');
        $paths = Configure::getInstance();
        $folder =& new Folder();
        $folder->cd(APP . 'plugins');

        // Get the list of plugins
        $Plugins = $folder->read();
        $Plugins = $Plugins[0];
        $arr = array();

        // Loop through the plugins
        foreach($Plugins as $pluginName) {
            // Change directory to the plugin
            $didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
            // Get a list of the files that have a file name that ends
            // with controller.php
            $files = $folder->findRecursive('.*_controller\.php');

            // Loop through the controllers we found in the plugins directory
            foreach($files as $fileName) {
                // Get the base file name
                $file = basename($fileName);

                // Get the controller name
                $file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
                if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
                    if (!App::import('Controller', $pluginName.'.'.$file)) {
                        debug('Error importing '.$file.' for plugin '.$pluginName);
                    } else {
                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = Inflector::humanize($pluginName) . "/" . $file;
                    }
                }
            }
        }
        return $arr;
    }

    // End functions for building ACOs --------------------------------------------------------------------


}
?>