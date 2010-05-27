<?php
$updateMenu = 'new Ajax.Updater("menu","' . Configure::read('relativeUrl') .
    '/layouts/menu",{method:"get",evalScripts:true});';

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
    $html->tag('li', 'Browsing by category/tag/user').
    $html->tag('li', 'Adding/editing/deleting items with pictures with real-time validation').
    $html->tag('li', 'RSS feed').
    $html->tag('li', 'Auto-updated latest items').
    $html->tag('li', 'Dynamic multi-level menu').
    $html->tag('li', 'Buying items using PayPal (sandbox of course)').
    $html->tag('li', 'Moderators and administrators can edit categories and users').
    $html->tag('li', 'Works even without JavaScript').
    $html->tag('li', 'Like button for Facebook (the most usable feature ;D)'));
?>
