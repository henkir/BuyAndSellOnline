<?php
class PurchasesController extends AppController {
    var $name = 'Purchases';
    
    function beforeFilter() {
	parent::beforeFilter();
    }

    function index() {
        $this->set('purchases', $this->Purchase->find('all'));
    }

    function view($id = null) {
        $this->Purchase->id = $id;
        $this->set('purchase', $this->Purchase->read());
    }

    function edit($id) {
	$this->set('purchases', $this->Purchase->find('all'));
	if (!empty($this->data)) {
	    if ($this->Purchase->save($this->data)) {
		$this->Session->setFlash('The purchase has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the purchase.');
	    }
	}
    }

    function add() {
	$this->set('purchases', $this->Purchase->find('all'));
	if (!empty($this->data)) {
	    if ($this->Purchase->save($this->data)) {
		$this->Session->setFlash('The purchase has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the purchase.');
	    }
	}
    }

    function delete($id) {
	$this->Purchase->delete($id);
	$this->Session->setFlash('The purchase has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

    
}

?>