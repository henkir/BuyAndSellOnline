<?php
class GroupsController extends AppController {
    var $name = 'Groups';

    function beforeFilter() {
        parent::beforeFilter();
        // TODO: allow index and view?
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

    function initGroups() {
	$groups = array(0 => array('alias' => 'members',
				   'model' => 'Group',
				   'foreign_key' => 0),
			1 => array('alias' => 'moderators',
				   'model' => 'Group',
				   'foreign_key' => 1),
			2 => array('alias' => 'administrators',
				   'model' => 'Group',
				   'foreign_key' => 2));

	foreach ($groups as $data) {
	    $this->Acl->Aro->create();
	    $this->Acl->Aro->save($data);
	}
    }

}

?>