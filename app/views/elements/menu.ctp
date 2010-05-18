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
        $ajax->link('Home',
            '/',
            array('update' => 'content')));

$browse = $html->tag('li',
          $ajax->link('Browse',
              array('controller' => 'items', 'action' => 'index'),
              array('update' => 'content')));

$addItem = $html->tag('li',
           $ajax->link('Add Item',
               array('controller' => 'items', 'action' => 'add'),
               array('update' => 'content')));

$categories = $html->tag('li',
              $ajax->link('Categories',
                  array('controller' => 'categories',
                      'action' => 'index'),
                  array('update' => 'content')));

$tags = $html->tag('li',
        $ajax->link('Tags',
            array('controller' => 'tags',
                'action' => 'index'),
            array('update' => 'content')));

$moderatorMenu = 'Moderator';

$adminMenu = 'Administrator';

$categoriesEdit = $html->tag('li',
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
          $html->link('Logout',
              array('controller' => 'users', 'action' => 'logout')));

$login = $html->tag('li',
         $ajax->link('Login',
             array('controller' => 'users', 'action' => 'login'),
             array('update' => 'content')));

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