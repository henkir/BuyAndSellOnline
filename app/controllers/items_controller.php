<?php
class ItemsController extends AppController {
    var $name = 'Items';
    var $components = array('FileUpload');
    var $paginate = array('limit' => 8, 'order' => array('Item.created' => 'desc'));
    
    function beforeFilter() {
	parent::beforeFilter();
	//$this->Auth->allowedActions = array('index', 'view', 'clear');
	$this->FileUpload->uploadDir = 'img/uploads';
	$this->FileUpload->fileModel = null;
    }

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

    function clear() {
	$this->Session->delete($this->name.'.keyword');
	$this->index();
	$this->render('index');
    }

    function _filterSearch() {
	$filters = array();
	if (!empty($this->data) && strlen($this->data['Item']['keyword']) > 0) {
	    $search = $this->data['Item']['keyword'];
	} elseif ($this->Session->check($this->name.'.keyword')) {
	    $search = $this->Session->read($this->name.'.keyword');
	}

	if (isset($search)) {
	    $search = strtolower($search);
	    $filters = array("LOWER(Item.name) LIKE '%".$search."%'".
			     "OR Item.price LIKE '%".$search."%'".
			     "OR Item.description LIKE '%".$search."%'");
	    $this->Session->write($this->name.'.keyword', $search);
	}

	return $filters;
    }

    function view($id = null) {
        $this->Item->id = $id;
        $this->set('item', $this->Item->read());
    }
    
    function preview($id = null) {
        $this->view($id);
        $this->render('preview');
    }

    function edit($id) {
	$this->set('items', $this->Item->find('all'));
	if (!empty($this->data)) {
	    if ($this->Item->save($this->data)) {
		$this->Session->setFlash('The item has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the item.');
	    }
	}
    }

    function add() {
	$this->set('categories', $this->Item->Category->find('list'));
	$this->set('tags', $this->Item->Tag->find('list'));
	if (!empty($this->data)) {
	    if ($this->FileUpload->success) {
		$this->data['Item']['image'] = $this->FileUpload->finalFile;
	    }

	    if ($this->Item->save($this->data)) {
		$this->Session->setFlash('The item has been saved.', 'default', array('class' => 'success'));
	    } else {
		// TODO: remove image if any
		$this->Session->setFlash('The item could not be saved.', 'default', array('class' => 'error'));
	    }
	}
    }

    function delete($id) {
	$this->Item->delete($id);
	$this->Session->setFlash('The item has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

    function terms() {

    }

    function buy($id) {

    }

}