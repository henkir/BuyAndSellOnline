<?php
// Just set the title of the page here, so we don't have to bother later.
$this->pageTitle = 'BuyAndSellOnline';

if ($session->flash()) {
    echo $session->flash();
 }

echo $html->tag('h2', 'BuyAndSellOnline');
echo $html->para(null, 'Welcome to BuyAndSellOnline!');
echo $html->para(null, 'At the moment, the following features are available (somewhat):'.
		 $html->tag('ul',
			    $html->tag('li', 'AJAX navigation and updating of menu').
			    $html->tag('li', 'OpenID login').
			    $html->tag('li', 'AJAX browsing and filtering of items').
			    $html->tag('li', 'Adding items with pictures')));
?>
