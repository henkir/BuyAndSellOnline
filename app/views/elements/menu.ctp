<?php

  /**
   * Prints a menu with AJAX links. Should be given the variables $loggedIn,
   * $moderator and $admin by using $this->set().
   *
   */

$relativeUrl = Configure::read('relativeUrl');

// Use this as complete callback function when the menu needs to be updated.
$updateMenu = 'new Ajax.Updater("menu","' . $relativeUrl .
    '/layouts/menu",{method:"get",evalScripts:true});';


$home = $html->tag('li',
        $html->link('Home',
            '/'));

$browse = $html->tag('li',
          $html->link('Browse',
          array('controller' => 'items', 'action' => 'index')));

$addItem = $html->tag('li',
           $html->link('Add Item',
               array('controller' => 'items', 'action' => 'add')));

$categories = $html->tag('li',
              $html->link('Categories',
                  array('controller' => 'categories',
                      'action' => 'index')));

$tags = $html->tag('li',
        $html->link('Tags',
            array('controller' => 'tags',
                'action' => 'index')));

$moderatorMenu = 'Moderator';

$adminMenu = 'Administrator';

$categoriesEdit = $html->tag('li',
                  $html->link('Categories',
                      array('controller' => 'categories',
                          'action' => 'edit')));

$users = $html->tag('li',
         $html->link('Users',
             array('controller' => 'users',
                 'action' => 'index')));

$logout = $html->tag('li',
          $html->link('Logout',
              array('controller' => 'users', 'action' => 'logout'),
              array('update' => 'content')));

$login = $html->tag('li',
         $html->link('Login',
             array('controller' => 'users', 'action' => 'login')));

$adminItems = '';
$menuItems = $home . $browse . $categories . $tags;
if ($loggedIn) {
    $menuItems .= $addItem;
 }
if ($admin) {
    $subMenu = $adminMenu;
 } elseif ($moderator) {
     $subMenu = $moderatorMenu;
   }
if ($moderator) {
    $adminItems .= $categoriesEdit;
 }
if ($admin) {
    $adminItems .= $users;
 }
if (isset($subMenu)) {
    $menuItems .= $html->tag('li',
                  $subMenu .
                  $html->tag('ul', $adminItems));
 }
if ($loggedIn) {
    $menuItems .= $logout;
 } else {
    $menuItems .= $login;
 }

echo $html->tag('ul', $menuItems);

?>