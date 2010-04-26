<?php
class PagesController extends AppController {
	var $name = 'Pages';
    var $uses = array();
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $layout = 'ajax_default';
    var $title_for_layout = 'BuyAndSellOnline';
    
    function display() {
        $this->set('title_for_layout', 'BuyAndSellOnline');
    }
    
}

?>