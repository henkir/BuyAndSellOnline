<?php
class GroupsController extends AppController {
    var $name = 'Groups';
    var $components = array('Acl');

    function beforeFilter() {
        parent::beforeFilter();
        // TODO: allow index and view?
    }

    function index() {
	$this->set('groups', $this->Group->find('all'));
    }

    /**
     * Gets the Group with the given id.
     *
     * @param id the id of the Group.
     */
    function view($id) {
        $this->Group->id = $id;
        $this->set('group', $this->Group->read());
    }

    /**
     * Edits the Group with the given id.
     *
     * @param id the id of the Group.
     */
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

    /**
     * Adds a Group with the data supplied in the form.
     */
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

    /**
     * Deletes the Group with the given id.
     *
     * @param id the id of the Group.
     */
    function delete($id) {
        $this->Group->delete($id);
        $this->Session->setFlash('The group has been deleted.');
        $this->redirect(array('action' => 'edit'));
    }

    /**
     * Creates the Groups in the AROs.
     */
    function initGroups() {
        $groups = array(0 => array('alias' => 'members',
                                   'model' => 'Group',
                                   'foreign_key' => 1),
                        1 => array('alias' => 'moderators',
                                   'model' => 'Group',
                                   'foreign_key' => 2),
                        2 => array('alias' => 'administrators',
                                   'model' => 'Group',
                                   'foreign_key' => 3));

        foreach ($groups as $data) {
            $this->Acl->Aro->create();
            $this->Acl->Aro->save($data);
        }
    }

}

?>