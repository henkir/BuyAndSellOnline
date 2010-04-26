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

}