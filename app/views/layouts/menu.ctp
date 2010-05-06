<?php

  /**
   * Prints the menu which depends on whether the user is logged in or not, is moderator or not and is admin or not.
   */

// TODO: check if a logged in user is a moderator
$moderator = $loggedIn;
$admin = $moderator;

$relativeUrl = Configure::read('relativeUrl');

// Use this as complete callback function when the menu needs to be updated.
$updateMenu = 'new Ajax.Updater(\'menu\', \''.$relativeUrl.'/layouts/menu\', { method: \'get\', evalScripts: true });';

echo '<ul>';
echo '<li>',
    $ajax->link('Home',
		array('controller' => 'pages', 'action' => 'display'),
		array('update' => 'content')),
    '</li>';
echo '<li>',
    $ajax->link('Browse',
		array('controller' => 'items', 'action' => 'index'),
		array('update' => 'content')),
    '</li>';
echo '<li>',
    $ajax->link('Categories',
		array('controller' => 'categories', 'action' => 'index'),
		array('update' => 'content')),
    '</li>';
echo '<li>',
    $ajax->link('Search',
		array('controller' => 'items', 'action' => 'search'),
		array('update' => 'content')),
    '</li>';

if ($admin) {
    echo '<li>',
$ajax->link('Admin panel',
array('controller' => 'pages', 'action' => 'display'),
array('update' => 'content')), '</li>';
 }

if ($loggedIn) {
    echo '<li>',
	$ajax->link('Logout',
		    array('controller' => 'users', 'action' => 'logout'),
		    array('update' => 'content',
			  'complete' => $updateMenu)),
	'</li>';
 } else {
    echo '<li>',
	$ajax->link('Login',
		    array('controller' => 'users', 'action' => 'login'),
		    array('update' => 'content')),
	'</li>';
}

echo '</ul>';

?>