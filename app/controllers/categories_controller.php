<?php
class CategoriesController extends AppController {
	var $name = 'Categories';
    var $components = array('RequestHandler');
    
    
    
    function index() {
        
        $this->set('categories', $this->Category->find('all'));
    }
    
}

?>