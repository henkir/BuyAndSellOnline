<?php
class LayoutsController extends AppController {
    var $name = 'Layouts';
    // This controller doesn't use any datasource.
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Gets the current menu.
     */
    function menu() {

    }

}

?>