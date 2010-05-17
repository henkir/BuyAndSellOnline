<?php
class ItemsController extends AppController {
    var $name = 'Items';
    var $components = array('FileUpload');
    var $helpers = array('Number');
    /**
     * Paginates the Items with a limit of 8 Items per page, sorted descending
     * on creation date.
     */
    var $paginate = array('limit' => 8,
                          'order' => array('Item.created' => 'desc'));

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allowedActions = array('index', 'view', 'clear');
        // Configure FileUpload
        $this->FileUpload->uploadDir = 'img/uploads';
        $this->FileUpload->fileModel = null;
    }

    /**
     * Gets all Items if id is null. If id is not null it gets the Item with that
     * id. If it is an RSS request it gets the Items for the RSS feed.
     */
    function index($id = null) {
        if ($id != null && $this->RequestHandler->isAjax()) {
            $this->Item->id = $id;
            $this->set('item', $this->Item->read());
        } elseif($this->RequestHandler->isRss()) {
            Configure::write('debug', 0);
            $this->set('items',
                       $this->Item->find('all',
                                         array('limit' => 20,
                                               'order' => 'Item.created DESC')));
        } else {
            $this->set('items', $this->Item->find('all'));
            $data = $this->paginate(null, $this->_filterSearch());
            $this->set('data', $data);
        }
    }

    /**
     * Gets the latest Items.
     *
     * @return the latest Items
     */
    function latest() {
	$items = $this->Item->find('all',
				   array('limit' => 6,
					 'order' => 'Item.created DESC'));
	$this->set('items', $items);
	return $items;
    }

    /**
     * Clears the filtering of Items.
     */
    function clear() {
        $this->Session->delete($this->name.'.keyword');
        $this->index();
        $this->render('index');
    }

    /**
     * Filters the Items to only show matching ones.
     */
    function _filterSearch() {
        $filters = array();
        // If the data submitted isn't empty, then we use the new keyword.
        // Otherwise, check if there is an old one in the session and use
        // that instead.
        if (!empty($this->data) && strlen($this->data['Item']['keyword']) > 0) {
            $search = $this->data['Item']['keyword'];
        } elseif ($this->Session->check($this->name.'.keyword')) {
            $search = $this->Session->read($this->name.'.keyword');
        }

        // If there is something to search for we create the filters.
        if (isset($search)) {
            $search = strtolower($search);
            $filters = array("LOWER(Item.name) LIKE '%".$search."%'".
                             "OR Item.price LIKE '%".$search."%'".
                             "OR Item.description LIKE '%".$search."%'");
            $this->Session->write($this->name.'.keyword', $search);
        }

        return $filters;
    }

    /**
     * Gets the Item with the given id.
     *
     * @param id the id of the Item
     */
    function view($id = null) {
        $this->Item->id = $id;
        $this->set('item', $this->Item->read());
    }

    /**
     * Gets the Item with the given id.
     *
     * @param id the id of the Item
     */
    function preview($id = null) {
        $this->view($id);
        $this->render('preview');
    }

    /**
     * Edits the Item with the given id.
     *
     * @param id the id of the Item
     */
    function edit($id) {
        $this->set('items', $this->Item->find('all'));
        if (!empty($this->data)) {
            if ($this->Item->save($this->data)) {
                $this->Session->setFlash('The item has been saved.',
					 'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed saving the item.',
					 'default', array('class' => 'error'));
            }
        }
    }

    /**
     * Adds the Item with the data supplied in the form.
     */
    function add() {
        $this->set('categories', $this->Item->Category->find('list'));
        $this->set('tags', $this->Item->Tag->find('list'));
        if (!empty($this->data)) {
	    print_r($this->data);
            if ($this->FileUpload->success) {
                $this->data['Item']['image'] = $this->FileUpload->finalFile;
            } else {
		$this->data['Item']['image'] = null;
	    }

	    if ($this->Session->check('Auth.User.id')) {
		$this->data['Item']['user_id'] = $this->Session->read('Auth.User.id');
		if ($this->Item->save($this->data)) {
		    $this->Session->setFlash('The item has been saved.',
					     'default', array('class' => 'success'));
            $this->redirect('/');
		} else {
		    if (!empty($this->data['Item']['image'])) {
			$this->_deleteImage($this->data['Item']['image']);
		    }
		    $this->Session->setFlash('The item could not be saved.',
					     'default', array('class' => 'error'));
		}
	    } else {
		if (!empty($this->data['Item']['image'])) {
		    $this->_deleteImage($this->data['Item']['image']);
                }
                $this->Session->setFlash('You need to be logged in.',
					 'default', array('class' => 'error'));
	    }


        }
    }

    function _deleteImage($name) {
	$file = new File(WWW_ROOT .
			 $this->FileUpload->uploadDir . '/' .
			 $name);
	$file->delete();
	$file->close();
    }

    /**
     * Deletes the Item with the given id.
     *
     * @param id the id of the Item
     */
    function delete($id) {
        if ($this->Item->delete($id)) {
	    $this->Session->setFlash('The item has been deleted.',
				     'default', array('class' => 'success'));
	} else {
	    $this->Session->setFlash('Failed deleting the item.',
				     'default', array('class' => 'error'));
	}
        $this->redirect(array('action' => 'edit'));
    }

    /**
     * Terms of agreement.
     */
    function terms() {

    }

    /**
     * Buys the Item with the given id.
     *
     * @param id the id of the Item
     */
    function buy($id) {

    }

}