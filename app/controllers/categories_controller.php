<?php
class CategoriesController extends AppController {
    var $name = 'Categories';
    
    function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allowedActions = array('index', 'view');
    }

    function index() {
        $this->set('categories', $this->Category->find('all'));
    }
   
    function view($id) {
	$this->Category->id = $id;
	$this->set('category', $this->Category->read());
    }

    function edit($id) {
	$this->set('categories', $this->Category->find('all'));
	if (!empty($this->data)) {
	    if ($this->Category->save($this->data)) {
		$this->Session->setFlash('The category has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the category.');
	    }
	}
    }

    function add() {
	$this->set('categories', $this->Category->find('all'));
	if (!empty($this->data)) {
	    if ($this->Category->save($this->data)) {
		$this->Session->setFlash('The category has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the category.');
	    }
	}
    }

    function delete($id) {
	$this->Category->delete($id);
	$this->Session->setFlash('The category has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

  }

?>