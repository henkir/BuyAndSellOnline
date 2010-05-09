<?php

  /**
   * Prints a menu with AJAX links. Should be given the variables $loggedIn, $moderator and $admin.
   * Example: $this->element('menu', array('loggedIn' => true, 'moderator' => true, 'admin' => false));
   */

$relativeUrl = Configure::read('relativeUrl');

// Use this as complete callback function when the menu needs to be updated.
$updateMenu = 'new Ajax.Updater("menu","'.$relativeUrl.'/layouts/menu",{method:"get",evalScripts:true});';

$menuItems = $html->tag('li',
			$ajax->link('Home',
				    array('controller' => 'pages', 'action' => 'display'),
				    array('update' => 'content'))).
    $html->tag('li',
	       $ajax->link('Browse',
			   array('controller' => 'items', 'action' => 'index'),
			   array('update' => 'content')));

if ($loggedIn) {
    $menuItems .= $html->tag('li',
			     $ajax->link('Add Item',
					 array('controller' => 'items', 'action' => 'add'),
					 array('update' => 'content')));
 }
    /*$html->tag('li',
	       $ajax->link('Categories',
			   array('controller' => 'categories', 'action' => 'index'),
			   array('update' => 'content')));*/

if ($admin) {
    /*$menuItems .= $html->tag('li',
			     $ajax->link('Admin panel',
					 array('controller' => 'pages', 'action' => 'display'),
					 array('update' => 'content')));*/
 }

if ($loggedIn) {
    $menuItems .= $html->tag('li',
			     $ajax->link('Logout',
					 array('controller' => 'users', 'action' => 'logout'),
					 array('update' => 'content',
					       'complete' => $updateMenu)));
 } else {
    $menuItems .= $html->tag('li',
			     $ajax->link('Login',
					 array('controller' => 'users', 'action' => 'login'),
					 array('update' => 'content')));
}

echo $html->tag('ul', $menuItems);
		
?>