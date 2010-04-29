<?php
class ItemsController extends AppController {
    var $name = 'Items';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array('RequestHandler');
    
    function index($id = null) {
 	if ($id != null && $this->RequestHandler->isAjax()) {
	    $this->Item->id = $id;
	    $this->set('item', $this->Item->read());
	} else {
	    $this->set('items', $this->Item->find('all'));
	}
    }
    
    function view($id = null) {
        $this->Item->id = $id;
        $this->set('item', $this->Item->read());
    }
    
    function preview($id = null) {
        $this->view($id);
        $this->render('preview');
    }
    
    function smallview($id = null) {
        $this->view($id);
        $this->render('smallview');
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
	$this->set('items', $this->Item->find('all'));
	if (!empty($this->data)) {
	    if ($this->Item->save($this->data)) {
		$this->Session->setFlash('The item has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the item.');
	    }
	}
    }

    function delete($id) {
	$this->Item->delete($id);
	$this->Session->setFlash('The item has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

    function buy($id) {

    }

    function search() {

    }

}