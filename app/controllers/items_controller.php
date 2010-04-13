<?php
class ItemsController extends AppController {
    var $name = 'Items';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array('RequestHandler');
    
    function index() {
        $this->set('itemInfo', '');
        $this->set('items', $this->Item->find('all'));
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

}