<?php
class PurchasesController extends AppController {
	var $name = 'Purchases';
    
    function index() {
        $this->set('purchases', $this->Purchase->find('all'));
    }
    
}

?>