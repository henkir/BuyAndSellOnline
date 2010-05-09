<?php
  /*
   * Prints the default layout for the web page, constructing the elements for later
   * AJAX manipulation.
   */

// Variables that simplify customizing the layout depending of the status of the user.

// TODO: check if a logged in user is a moderator
$moderator = $loggedIn;
$admin = $moderator;

$this->set('loggedIn', $loggedIn);
$this->set('moderator', $moderator);
$this->set('admin', $admin);

$relativeUrl = Configure::read('relativeUrl');
$ip = Configure::read('ip');

// Print the document type and initial html tag.
echo $html->docType();
echo '<html xmlns="http://www.w3.org/1999/xhtml"><head>';
// Print the charset of the document, defaults to utf-8.
echo $html->charset();
// Print the title.
echo $html->tag('title', $title_for_layout);
// Print location of favicon.
echo '<link rel="shortcut icon" href="'.$relativeUrl.'/favicon.ico" type="image/x-icon" />';
echo '<link rel="alternate" type="application/rss+xml" title=”BuyAndSellOnline" href="http://'.$ip .$relativeUrl.'/items/index.rss" />';
// Print the CSS files to use.
echo $html->css('/css/blueprint/screen.css', 'stylesheet', array('media' => 'screen, projection'));
echo $html->css('/css/blueprint/print.css', 'stylesheet', array('media' => 'print'));
echo '<!--[if lt IE 8]>'.$html->css('/css/blueprint/ie.css').'<![endif]-->';
echo $html->css('/css/buyandsellonline.css', 'stylesheet', array('media' => 'screen, projection'));
// Print the javascript files to use.
echo $javascript->link('/js/prototype.js');
echo $javascript->link('/js/scriptaculous.js');
echo $javascript->link('/js/shortcut.js');
// Print any eventual additional scripts.
echo $scripts_for_layout;
echo '</head><body>';
// Print container for the page. Class needs to be container for Blueprint to work.
echo $ajax->div('container', array('class' => 'container'));
// Print the header.
echo $ajax->div('header', array('class' => 'span-24 last'));
echo $ajax->div('banner', array('class' => 'span-24 last'));
// Create a clickable banner.
echo $ajax->link($html->image('/img/banner.png', array('alt' => 'BuyAndSellOnline', 'style' => 'border:0')), array('controller' => 'pages', 'action' => 'display'), array('update' => 'content'), null, false);
echo $ajax->divEnd('banner');
echo $ajax->divEnd('header');
// Print the menu, using the element.
echo $ajax->div('menu', array('class' => 'span-4'));
echo $this->element('menu');
echo $ajax->divEnd('menu');
// Print the main content div.
echo $ajax->div('content', array('class' => 'span-20 last'));
// Any content is printed out.
echo $content_for_layout;
echo $ajax->divEnd('content');
// Print the footer.
echo $ajax->div('footer', array('class' =>'span-24 last'));
echo $ajax->divEnd('footer');
echo $ajax->divEnd('container');
echo '</body></html>';
?>
