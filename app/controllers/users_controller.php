<?php
class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('Openid', 'Acl');
    var $facebook;

    function beforeFilter() {
        parent::beforeFilter();
        $this->facebook = new Facebook(array(
                              'appId'  => '120588011307924',
                              'secret' => '06905be69e3290747a3e4c7c91ca1479',
                              'cookie' => true
                          ));
        $this->Auth->allowedActions =
            array('index', 'view', 'viewitems', 'login', 'logout', 'build_acl', 'initDB');
    }

    function _allowed($userId = null) {
        if ($userId == null) {
            return $this->Auth->user('group_id') >= 2;
        } else {
            $user = $this->User->read($userId);
            if ($this->Auth->user('id') == $userId
                || ($this->Auth->user('group_id') >= 2
                    && $this->Auth->user('group_id') >= $user['User']['group_id'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Gets all Users.
     */
    function index() {
        $paginate = array('limit' => Configure::read('usersPerPage'),
                          'order' => array('User.created' => 'desc'));
        $data = $this->paginate();
        $this->set('data', $data);
    }

    function viewitems($id = null) {
        $this->paginate = array('limit' => Configure::read('itemsPerPage'),
                          'order' => array('Item.created' => 'desc'),
                          'conditions' => array('Item.sold = ' => false));
        if ($id == null) {
            if ($this->Session->check('Auth.User.id')) {
                $id = $this->Session->read('Auth.User.id');
                $data = $this->paginate('Item', array('Item.user_id = ' => $id));
                $this->set('data', $data);
                $this->User->id = $id;
                $this->set('user', $this->User->read());
            }
        } else {
            $data = $this->paginate('Item', array('Item.user_id = ' => $id));
            $this->set('data', $data);
            $this->User->id = $id;
            $this->set('user', $this->User->read());
        }

    }

    /**
     * Gets the Users with the given id.
     *
     * @param id the id of the User
     */
    function view($id = null) {
        $this->User->id = $id;
        $user = $this->User->read();
        $this->set('user', $user);
        return $user;
    }

    /**
     * Edits the Users with the given id.
     *
     * @param id the id of the User
     */
    function edit($id = null) {
        if ($this->_allowed($id)) {
            $paginate = array('limit' => Configure::read('usersPerPage'),
                        'order' => array('User.created' => 'desc'),
                        'conditions' =>
                        array('User.group_id <= ' =>
                            $this->Session->read('Auth.User.group_id')));
            if (!empty($this->data)) {
                $this->data['User']['group_id'] = $this->data['User']['groups'];
                if ($this->User->save($this->data)) {
                    $this->Session->setFlash('The user has been saved.',
                        'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('Failed saving the user.',
                        'default', array('class' => 'error'));
                }

            }
            if ($id == null) {
                $this->set('data', $this->paginate());
            } else {
                $this->User->id = $id;
                $this->set('groups', $this->User->Group->find('list',
                        array('conditions' =>
                            array('Group.id <= ' =>
                                $this->Session->read('Auth.User.group_id')))));
                $this->set('user', $this->User->read());
                $this->set('users', $this->User->find('list'));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Adds a Users with the given data.
     *
     * @param data the data of the User
     * @return the id of the new User
     */
    function _add($data) {
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
        $this->Session->setFlash('The user has been deleted.',
            'default', array('class' => 'success'));
        $this->redirect(array('action' => 'edit'));
    }

    /**
     * Registers a user the traditional way.
     */
    function register() {
        if (!empty($this->data)) {
            $this->data['User']['password'] =
                $this->Auth->password($this->data['User']['passwd']);
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

    function check() {
        return $this->_facebook();
    }

    function _facebook() {
        return $this->_getFacebookCookie('120588011307924', '06905be69e3290747a3e4c7c91ca1479');
    }

    function _getFacebookCookie($app_id, $application_secret) {
        if (isset($_COOKIE['fbs_' . $app_id])) {
            $args = array();
            parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
            ksort($args);
            $payload = '';
            foreach ($args as $key => $value) {
                if ($key != 'sig') {
                    $payload .= $key . '=' . $value;
                }
            }
            if (md5($payload . $application_secret) != $args['sig']) {
                return null;
            }
            return $args;
        }
        return null;
    }

    /**
     * Logs in a user with OpenID, or the traditional way.
     */
    function login() {

        $returnTo = 'http://' . Configure::read('ip') .
            '/BuyAndSellOnline/users/login';
        $facebookCookie = $this->_facebook();
        $this->set('facebookCookie', $facebookCookie);
        if ($facebookCookie != null && !$this->Session->check('User.Auth.id')) {
            if (!empty($facebookCookie['uid'])) {
                $user = $this->User->findByFacebookid($facebookCookie['uid']);
                $data['facebookid'] = $facebookCookie['uid'];
                $user = json_decode(file_get_contents(
                            'https://graph.facebook.com/me?access_token=' .
                            $facebookCookie['access_token']));
                $this->autoRender = false;
                $data['email'] = $user->email;
                $name = explode(' ', $user->name);
                $data['first_name'] = $name[0];
                $data['last_name'] = $name[1];
                $this->_testUser($data);
                $this->Session->setFlash(
                    'Successfully authenticated with Facebook.',
                    'default', array('class' => 'success'));
                $this->redirect('/');
            }
            // If data isn't empty, try to authenticate user using OpenID.
        } elseif (!empty($this->data)) {
            if (!empty($this->data['OpenidUrl']['openid'])) {
                try {
                    $this->Openid->authenticate(
                        $this->data['OpenidUrl']['openid'],
						$returnTo,
						'http://' . Configure::read('ip') .
                        '/BuyAndSellOnline',
                        array('sreg_required' =>
                            array('email', 'nickname', 'fullname')));
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
            $sregResponse =
                Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            $sreg = $sregResponse->contents();
            $sreg['openid'] = $_GET['openid_identity'];
            // New user becomes a member
            $sreg['group_id'] = 1;
            $this->_testUser($sreg);
            $this->Session->setFlash('Successfully authenticated with OpenID.',
                'default', array('class' => 'success'));

            $this->redirect('/');
        }
    }

    /**
     * Tests if the user has logged in before and log in the user.
     */
    function _testUser($data) {
        if (isset($data['openid'])) {
            $test = $this->User->findByOpenid($data['openid']);
        } elseif (isset($data['facebookid'])) {
            $test = $this->User->findByFacebookid($data['facebookid']);
        }

        if ($test) {
            // User has logged in before, recreate data.
            $data = array_merge($test['User'], $data);
            $this->_recreate($data);
            $id = $test['User']['id'];
        } else {
            // First time, add user.
            $id = $this->_add($data);
        }
        // Finally log in the user.
        $this->Auth->login($id);
    }

    // Recreate user given data.
    function _recreate($data) {
        $this->data['User'] = $data;
        if (!empty($this->data['fullname'])) {
            $name = explode(' ', $this->data['fullname']);
            $this->data['first_name'] = $name[0];
            $this->data['last_name'] = $name[1];
        }
        $this->User->save($this->data['User']);
    }

    /**
     * Logs out the User currently logged in.
     */
    function logout() {
        $this->Session->setFlash('Logged out.',
            'default', array('class' => 'success'));
        $facebookCookie = $this->_facebook();

        $this->redirect($this->Auth->logout());
    }

    /**
     * Sets privileges for different groups, should be made private in production
     */
    function initDB() {
        if (!Configure::read('debug')) {
            return;
        }
        $this->build_acl();
        $this->requestAction(
            array('controller' => 'groups', 'action' => 'initGroups'));

        $group =& $this->User->Group;
        // Administrators
        $group->id = 3;
        $this->Acl->allow($group, 'controllers');
        // Moderators
        $group->id = 2;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'controllers/Items');
        $this->Acl->allow($group, 'controllers/Countries');
        $this->Acl->allow($group, 'controllers/Categories');
        $this->Acl->allow($group, 'controllers/Purchases/index');
        $this->Acl->allow($group, 'controllers/Purchases/view');
        $this->Acl->allow($group, 'controllers/Purchases/confirm');
        $this->Acl->allow($group, 'controllers/Purchases/edit');
        $this->Acl->allow($group, 'controllers/Groups/index');
        $this->Acl->allow($group, 'controllers/Groups/view');
        $this->Acl->allow($group, 'controllers/Users');
        $this->Acl->deny($group, 'controllers/Users/delete');
        // Members
        $group->id = 1;
        $this->Acl->deny($group, 'controllers');
        $this->Acl->allow($group, 'controllers/Items');
        $this->Acl->allow($group, 'controllers/Categories/index');
        $this->Acl->allow($group, 'controllers/Categories/view');
        $this->Acl->allow($group, 'controllers/Purchases/index');
        $this->Acl->allow($group, 'controllers/Purchases/view');
        $this->Acl->allow($group, 'controllers/Purchases/confirm');
        $this->Acl->allow($group, 'controllers/Groups/index');
        $this->Acl->allow($group, 'controllers/Groups/view');
        $this->Acl->allow($group, 'controllers/Users');
        $this->Acl->deny($group, 'controllers/Users/delete');

        $data = array(0 => array('User' => array('username' => 'test1',
                                         'password' => '8c22cd0aea08642744734cea168df020aeb6b593',
                                         'email' => 'testone@mail.com',
                                         'nickname' => 'test',
                                         'first_name' => 'Test',
                                         'last_name' => 'One',
                                         'country_id' => 'SE',
                                         'group_id' => 1)),
                1 => array('User' => array('username' => 'test2',
                                   'password' => '320501134148feae5eedbc07cdb71923be638471',
                                   'email' => 'testtwo@mail.com',
                                   'nickname' => 'test2',
                                   'first_name' => 'Test',
                                   'last_name' => 'Two',
                                   'country_id' => 'SE',
                                   'group_id' => 1)),
                2 => array('User' => array('username' => 'test3',
                                   'password' => 'a56536f0ca6cd3ae739124af712d7bf23be8bd9d',
                                   'email' => 'testthree@mail.com',
                                   'nickname' => 'test3',
                                   'first_name' => 'Test',
                                   'last_name' => 'Three',
                                   'country_id' => 'US',
                                   'group_id' => 2)),
                3 => array('User' => array('username' => 'admin',
                                   'password' => '28c3a486a0ea38fb26e6c1ed18f93efd5b839b9a',
                                   'email' => 'admin@istrator.com',
                                   'nickname' => 'admin',
                                   'first_name' => 'Admin',
                                   'last_name' => 'Istrator',
                                   'country_id' => 'NO',
                                   'group_id' => 3)));

        foreach ($data as $user) {
            $this->User->create();
            $this->User->save($user);
        }


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