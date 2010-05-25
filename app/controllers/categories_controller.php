<?php
class CategoriesController extends AppController {
    var $name = 'Categories';
    var $helpers = array('Form', 'Number');

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allowedActions = array('index', 'view');
    }

    /**
     * Gets all Categories.
     */
    function index() {
        $paginate = array('limit' => Configure::read('categoriesPerPage'),
                          'order' => array('Category.name' => 'asc'));
        $data = $this->paginate();
        $this->set('data', $data);
    }

    /**
     * Gets the Category with the given id.
     *
     * @param id the id of the Category
     */
    function view($id) {
        $this->paginate = array('limit' => Configure::read('itemsPerPage'),
                          'order' => array('Item.name' => 'desc'),
                          'conditions' => array('Item.category_id = ' => $id));
        $this->Category->id = $id;
        $category = $this->Category->read();
        $this->set('category', $category);
        $data = $this->paginate('Item');
        $this->set('data', $data);
    }

    /**
     * Edits the Category with the given id.
     *
     * @param id the id of the Category
     */
    function edit($id = null) {
        $paginate = array('limit' => Configure::read('categoriesPerPage'),
                          'order' => array('Category.name' => 'asc'));
        if (!empty($this->data)) {
            if ($this->Category->save($this->data)) {
                $this->Session->setFlash('The category has been saved.',
                    'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed saving the category.',
                    'default', array('class' => 'error'));
            }

        }
        if ($id == null) {
            $this->set('data', $this->paginate());
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
            $this->data['Category']['category_id'] =
                $this->data['Category']['category'];

            if ($this->Category->save($this->data)) {
                $this->Session->setFlash('The category has been saved.',
                    'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed saving the category.',
                    'default', array('class' => 'error'));
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
	    $this->Session->setFlash('The category has been deleted.',
            'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('Failed deleting the category.',
                'default', array('class' => 'error'));
        }
        $this->redirect(
            array('controller' => 'categories', 'action' => 'edit'));
    }

  }

?>