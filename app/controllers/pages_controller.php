<?php
class PagesController extends AppController {
    var $name = 'Pages';
    var $uses = array();
    var $helpers = array('Html', 'Javascript', 'Ajax');

    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * First action when a user entered the site.
     */
    function display() {

    }

}

?>