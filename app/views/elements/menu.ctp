<?php

  /**
   * Prints a menu with AJAX links. Should be given the variables $loggedIn,
   * $moderator and $admin by using $this->set().
   *
   */

// Use this as complete callback function when the menu needs to be updated.
$updateMenu = 'new Ajax.Updater("menu","' .
    $html->url(array('controller' => 'layouts', 'action' => 'menu')) .
    '",{method:"get",evalScripts:true});';


// Create all links.

$home = $html->tag('li',
        $ajax->link('Home',
            '/',
            array('update' => 'content',
                'indicator' => 'spinner')));

$browse = $html->tag('li',
          $ajax->link('Browse',
              array('controller' => 'items', 'action' => 'index'),
              array('update' => 'content',
                  'indicator' => 'spinner')));

$addItem = $html->tag('li',
           $ajax->link('Add Item',
               array('controller' => 'items', 'action' => 'add'),
               array('update' => 'content',
                   'indicator' => 'spinner')));

$myItems = $html->tag('li',
           $ajax->link('My Items',
               array('controller' => 'users', 'action' => 'viewitems'),
               array('update' => 'content',
                   'indicator' => 'spinner')));

$myItems = $html->tag('li', $html->tag('span', 'My Items »') .
           $html->tag('ul', $html->tag('li', $addItem) .
               $html->tag('li', $myItems)));

$myPurchases = $html->tag('li',
               $ajax->link('My Purchases',
                   array('controller' => 'purchases', 'action' => 'index'),
                   array('update' => 'content',
                       'indicator' => 'spinner')));

$categories = $html->tag('li',
              $ajax->link('Categories',
                  array('controller' => 'categories',
                      'action' => 'index'),
                  array('update' => 'content',
                      'indicator' => 'spinner')));

$tags = $html->tag('li',
        $ajax->link('Tags',
            array('controller' => 'tags',
                'action' => 'index'),
            array('update' => 'content',
                'indicator' => 'spinner')));

$moderatorMenu = $html->tag('span', 'Moderator »');

$adminMenu = $html->tag('span', 'Administrator »');

$categoriesEdit = $html->tag('li',
                  $ajax->link('Categories',
                      array('controller' => 'categories',
                          'action' => 'edit'),
                      array('update' => 'content',
                          'indicator' => 'spinner')));

$users = $html->tag('li',
         $ajax->link('Users',
             array('controller' => 'users',
                 'action' => 'index'),
             array('update' => 'content',
                 'indicator' => 'spinner')));

$usersEdit = $html->tag('li',
             $ajax->link('Users',
                 array('controller' => 'users',
                     'action' => 'edit'),
                 array('update' => 'content',
                     'indicator' => 'spinner')));

// Login/logout links
$logout = $html->tag('li',
          $html->link('Logout',
              array('controller' => 'users', 'action' => 'logout')));

$login = $html->tag('li',
         $ajax->link('Login',
             array('controller' => 'users', 'action' => 'login'),
             array('update' => 'content',
                 'indicator' => 'spinner')));

// Search form
$search = $form->create('Search', array('url' => array('controller' => 'items', 'action' => 'index'))) .
    $form->input('keyword', array('label' => false)) .
    $ajax->submit('Search', array('url' => array('controller' => 'items',
                                         'action' => 'index'),
            'update' => 'content',
            'indicator' => 'spinner',
            'complete' => "Form.Element.setValue('SearchKeyword', '')")) .
    $form->end();

// Construct menu using loggedIn, moderator and admin variables.
$adminItems = '';
$menuItems = $home . $browse . $categories /*. $tags*/;
if ($loggedIn) {
    $menuItems .= $myItems;
    $menuItems .= $myPurchases;
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
    $adminItems .= $usersEdit;
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

//$menuItems .= $search;

// Get users display name.
$displayName = '';
if (isset($userId)) {
    $user = $this->requestAction('/users/view/' . $userId);
    $displayName = $user['User']['nickname'];
    if (empty($displayName)) {
        $displayName = $user['User']['first_name'] . $user['User']['last_name'];
    }
 }

echo $html->div(null, $html->para(null, $displayName) .
    $html->tag('ul', $menuItems) . $search, array('id' => 'menu'));


?>