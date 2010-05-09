<?php
class PagesController extends AppController {
    var $name = 'Pages';
    var $uses = array();
    var $helpers = array('Html', 'Javascript', 'Ajax');

    function beforeFilter() {
	parent::beforeFilter();
    }
    
    function display() {
        $this->set('title', 'BuyAndSellOnline');
    }
    
}

?>