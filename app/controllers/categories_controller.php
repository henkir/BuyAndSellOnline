<?php
class CategoriesController extends AppController {
    var $name = 'Categories';
    var $helpers = array('Form');

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allowedActions = array('index', 'view');
    }

    /**
     * Gets all Categories.
     */
    function index() {
        $this->set('categories', $this->Category->find('all'));
    }

    /**
     * Gets the Category with the given id.
     *
     * @param id the id of the Category
     */
    function view($id) {
        $this->Category->id = $id;
        $this->set('category', $this->Category->read());
    }

    /**
     * Edits the Category with the given id.
     *
     * @param id the id of the Category
     */
    function edit($id = null) {
	if (!empty($this->data)) {
            if ($this->Category->save($this->data)) {
                $this->Session->setFlash('The category has been saved.', 'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed saving the category.', 'default', array('class' => 'error'));
            }

        }
	if ($id == null) {
	    $this->set('categories', $this->Category->find('all'));
	} else {
	    $this->Category->id = $id;
	    $this->set('category', $this->Category->read());
	    $this->set('categories', $this->Category->find('list'));
	}
    }

    /**
     * Adds a Category with the data supplied in the form.
     */
    function add() {
        if (!empty($this->data)) {
	    // Some manipulation required to save data.
	    $this->data['Category']['category_id'] = $this->data['Category']['category'];
            if ($this->Category->save($this->data)) {
                $this->Session->setFlash('The category has been saved.', 'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed saving the category.', 'default', array('class' => 'error'));
            }
        }
	$this->set('categories', $this->Category->find('list'));
    }

    /**
     * Deletes the Category with the given id.
     *
     * @param id the id of the Category
     */
    function delete($id) {
        if ($this->Category->delete($id)) {
	    $this->Session->setFlash('The category has been deleted.', 'default', array('class' => 'success'));
	} else {
	    $this->Session->setFlash('Failed deleting the category.', 'default', array('class' => 'error'));
	}
        $this->redirect(array('controller' => 'categories', 'action' => 'edit'));
    }

  }

?>