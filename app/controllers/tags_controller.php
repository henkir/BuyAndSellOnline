<?php
class TagsController extends AppController {
    var $name = 'Tags';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array('RequestHandler');
    
    function index() {
        $this->set('tagInfo', '');
        $this->set('tags', $this->Tag->find('all'));
    }
    
    function view($id = null) {
        $this->Tag->id = $id;
        $this->set('tag', $this->Tag->read());
    }

    function edit($id) {
	$this->set('tags', $this->Tag->find('all'));
	if (!empty($this->data)) {
	    if ($this->Tag->save($this->data)) {
		$this->Session->setFlash('The tag has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the tag.');
	    }
	}
    }

    function add() {
	$this->set('tags', $this->Tag->find('all'));
	if (!empty($this->data)) {
	    if ($this->Tag->save($this->data)) {
		$this->Session->setFlash('The tag has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the tag.');
	    }
	}
    }

    function delete($id) {
	$this->Tag->delete($id);
	$this->Session->setFlash('The tag has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }

}