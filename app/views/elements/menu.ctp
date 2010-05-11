<?php

  /**
   * Prints a menu with AJAX links. Should be given the variables $loggedIn, $moderator and $admin.
   * Example: $this->element('menu', array('loggedIn' => true, 'moderator' => true, 'admin' => false));
   */

$relativeUrl = Configure::read('relativeUrl');

// Use this as complete callback function when the menu needs to be updated.
$updateMenu = 'new Ajax.Updater("menu","'.$relativeUrl.'/layouts/menu",{method:"get",evalScripts:true});';


$home = $html->tag('li',
		   $ajax->link('Home',
			       array('controller' => 'pages', 'action' => 'display'),
			       array('update' => 'content')));

$browse = $html->tag('li',
		     $ajax->link('Browse',
				 array('controller' => 'items', 'action' => 'index'),
				 array('update' => 'content')));

$addItem = $html->tag('li',
		      $ajax->link('Add Item',
				  array('controller' => 'items', 'action' => 'add'),
				  array('update' => 'content')));

$moderatorMenu = 'Moderator';

$adminMenu = 'Administrator';

$categories = $html->tag('li',
			 $ajax->link('Categories',
				     array('controller' => 'categories',
					   'action' => 'edit'),
				     array('update' => 'content')));

$users = $html->tag('li',
		    $ajax->link('Users',
				array('controller' => 'users',
				      'action' => 'index'),
				array('update' => 'content')));

$logout = $html->tag('li',
		     $ajax->link('Logout',
				 array('controller' => 'users', 'action' => 'logout'),
				 array('update' => 'content',
				       'complete' => $updateMenu)));

$login = $html->tag('li',
		    $ajax->link('Login',
				array('controller' => 'users', 'action' => 'login'),
				array('update' => 'content')));

$adminItems = '';
$menuItems = $home . $browse;
if ($loggedIn) {
    $menuItems .= $addItem;
 }
if ($admin) {
    $subMenu = $adminMenu;
 } elseif ($moderator) {
     $subMenu .= $moderatorMenu;
   }
if ($moderator) {
    $adminItems .= $categories;
 }
if ($admin) {
    $adminItems .= $users;
 }
if (isset($subMenu)) {
    $menuItems .= $html->tag('li',
			     $subMenu .
			     $html->tag('ul', $adminItems, array('class' => 'bullets')));
 }
if ($loggedIn) {
    $menuItems .= $logout;
 } else {
    $menuItems .= $login;
 }

echo $html->tag('ul', $menuItems);

?>