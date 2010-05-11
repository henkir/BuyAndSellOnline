<?php
class PurchasesController extends AppController {
    var $name = 'Purchases';

    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Gets all Purchases.
     */
    function index() {
        $this->set('purchases', $this->Purchase->find('all'));
    }

    /**
     * Gets the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
    function view($id = null) {
        $this->Purchase->id = $id;
        $this->set('purchase', $this->Purchase->read());
    }

    /**
     * Edits the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
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

    /**
     * Adds a Purchase with the data supplied in the form.
     */
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

    /**
     * Deletes the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
    function delete($id) {
        $this->Purchase->delete($id);
        $this->Session->setFlash('The purchase has been deleted.');
        $this->redirect(array('action' => 'edit'));
    }


}

?>