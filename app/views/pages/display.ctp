<?php
$updateMenu = 'new Ajax.Updater("menu","' .
      $html->url(array('controller' => 'layouts', 'action' => 'menu')) .
          '",{method:"get",evalScripts:true});';

if ($session->flash()) {
    $session->flash();
    echo $this->element('hide_flash');
    echo $javascript->codeBlock($updateMenu);
    echo $javascript->blockEnd();
 }
echo $html->tag('h2', 'BuyAndSellOnline');
echo $html->para(null, 'Welcome to BuyAndSellOnline!');
echo $html->para(null, 'At the moment, the following features are available:');
echo $html->tag('ul',
    $html->tag('li', 'AJAX navigation and updating of menu').
    $html->tag('li', 'OpenID/Facebook login').
    $html->tag('li', 'AJAX browsing and filtering of items').
    $html->tag('li', 'Browsing by category/user').
    $html->tag('li', 'Adding/editing/deleting items with pictures with real-time validation').
    $html->tag('li', 'RSS feed').
    $html->tag('li', 'Auto-updated latest items').
    $html->tag('li', 'Dynamic multi-level menu').
    $html->tag('li', 'Buying items using PayPal (sandbox of course)').
    $html->tag('li', 'Moderators and administrators can edit categories and users').
    $html->tag('li', 'Works even without JavaScript').
    $html->tag('li', 'Like button for Facebook (the most usable feature ;D)'));
echo $html->para(null, 'We are using the following software/frameworks/techniques:');
echo $html->tag('ul',
    $html->tag('li', 'Apache web server').
    $html->tag('li', 'PHP').
    $html->tag('li', 'MySQL').
    $html->tag('li', 'CakePHP').
    $html->tag('li', 'Prototype').
    $html->tag('li', 'Scriptaculous').
    $html->tag('li', 'AJAX').
    $html->tag('li', 'Blueprint CSS').
    $html->tag('li', 'OpenID').
    $html->tag('li', 'Facebook').
    $html->tag('li', 'PayPal').
    $html->tag('li', 'JavaScript field validation'));
?>
