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


echo $javascript->codeBlock("
window.fbAsyncInit = function() {
        FB.init({appId: '120588011307924', status: true, cookie: true,
                 xfbml: true});
      };
      (function() {
        var e = document.createElement('script');
        e.type = 'text/javascript';
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
FB.Event.subscribe('auth.login', function(response) {
        window.location.reload();
      });");
echo $javascript->blockEnd();

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

$myItems = $html->tag('li',
           $ajax->link('My Items',
               array('controller' => 'users', 'action' => 'items'),
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

$search = $form->create('Search', array('controller' => 'items', 'action' => 'index')) .
    $form->input('keyword', array('label' => false)) .
    $ajax->submit('Search', array('url' => array('controller' => 'items',
                                         'action' => 'index'),
            'update' => 'content',
            'complete' => "Form.Element.setValue('SearchKeyword', '')")) . $form->end();

$adminItems = '';
$menuItems = $home . $browse . $categories . $tags;
if ($loggedIn) {
    $menuItems .= $addItem;
    $menuItems .= $myItems;
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

$menuItems .= $search;

echo $html->tag('ul', $menuItems);
echo '<fb:like href="http://94.254.42.77/BuyAndSellOnline/" layout="button_count"></fb:like>';
?>