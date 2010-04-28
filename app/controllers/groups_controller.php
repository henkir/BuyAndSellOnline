<?php
class GroupsController extends AppController {
    var $name = 'Groups';

    function beforeFilter() {
        parent::beforeFilter();
        
    }

    function view($id) {
	$this->Group->id = $id;
	$this->set('group', $this->Group->read());
    }

    function edit($id) {
	$this->set('groups', $this->Group->find('all'));
	if (!empty($this->data)) {
	    if ($this->Group->save($this->data)) {
		$this->Session->setFlash('The group has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the group.');
	    }
	}
    }

    function add() {
	$this->set('groups', $this->Group->find('all'));
	if (!empty($this->data)) {
	    if ($this->Group->save($this->data)) {
		$this->Session->setFlash('The group has been saved.');
	    } else {
		$this->Session->setFlash('Failed saving the group.');
	    }
	}
    }

    function delete($id) {
	$this->Group->delete($id);
	$this->Session->setFlash('The group has been deleted.');
	$this->redirect(array('action' => 'edit'));
    }


}

?>