<?php
class TagsController extends AppController {
    var $name = 'Tags';
    var $helpers = array('Number');
    var $components = array('RequestHandler');

    var $paginate = array('limit' => 20,
                          'order' => array('Tag.name' => 'asc'));

    function index() {
        $this->set('tagInfo', '');
        $this->set('tags', $this->Tag->find('all'));
        $data = $this->paginate();
        $this->set('data', $data);
    }

    function view($id = null) {
        $this->paginate = array('Item' =>
                          array('limit' => 8, 'joins' => array(
                                  array(
                                      'table' => 'items_tags',
                                      'alias' => 'ItemsTag',
                                      'type' => 'inner',
                                      'conditions'=> array('ItemsTag.item_id = Item.id')
                                  ),
                                  array(
                                      'table' => 'tags',
                                      'alias' => 'Tag',
                                      'type' => 'inner',
                                      'conditions'=> array(
                                          'Tag.id = ItemsTag.tag_id',
                                          'Tag.id' => $id
                                      )))));
        $this->Tag->id = $id;
        $this->set('tag', $this->Tag->read());
        $data = $this->paginate('Item');
        $this->set('data', $data);
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