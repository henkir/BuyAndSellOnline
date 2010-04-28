<?php
class PagesController extends AppController {
    var $name = 'Pages';
    var $uses = array();
    var $helpers = array('Html', 'Javascript', 'Ajax');
    
    function display() {
        $this->set('title', 'BuyAndSellOnline');
    }
    
}

?>