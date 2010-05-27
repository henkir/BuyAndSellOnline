<?php
  /*
   * Prints the default layout for the web page, constructing the elements for later
   * AJAX manipulation.
   */

// Variables that simplify customizing the layout depending of the status of the user.

if (isset($loggedIn)) {
    $this->set('loggedIn', $loggedIn);
    $this->set('moderator', $moderator);
    $this->set('admin', $admin);
 } else {
$this->set('loggedIn', false);
    $this->set('moderator', false);
    $this->set('admin', false);
 }

$relativeUrl = Configure::read('relativeUrl');
$ip = Configure::read('ip');

// Print the document type and initial html tag.

// HTML head ///////////////////////////////////////////////////////////////////
echo $html->docType();
echo '<html xmlns="http://www.w3.org/1999/xhtml"><head>';
// Print the charset of the document, defaults to utf-8.
echo $html->charset();
// Print the title.
echo $html->tag('title', $title_for_layout);
// Print keywords and description for the site.
echo $html->meta(null, null, array('name' => 'keywords',
        'content' => 'BuyAndSellOnline, buy, sell, openid, facebook'));
echo $html->meta(null, null, array('name' => 'description',
        'content' =>
        'BuyAndSellOnline allows you to sign in using OpenID or Facebook, buying and selling items.'));
// Print location of favicon.
echo '<link rel="shortcut icon" href="' . $relativeUrl . '/favicon.ico" type="image/x-icon" />';
// Print the RSS location.
echo '<link rel="alternate" type="application/rss+xml" title="BuyAndSellOnline" href="http://' . $ip . $relativeUrl . '/items/index.rss" />';
// Print the CSS files to use.
echo $html->css('/css/blueprint/screen.css', 'stylesheet', array('media' => 'screen, projection'));
echo $html->css('/css/blueprint/print.css', 'stylesheet', array('media' => 'print'));
echo '<!--[if lt IE 8]>'.$html->css('/css/blueprint/ie.css').'<![endif]-->';
echo $html->css('/css/validation.css', 'stylesheet', array('media' => 'screen, projection'));
echo $html->css('/css/buyandsellonline.css', 'stylesheet', array('media' => 'screen, projection'));
// Print the javascript files to use.
echo $javascript->link('prototype.js');
echo $javascript->link('scriptaculous.js');
echo $javascript->link('validation.js');
// Print any eventual additional scripts.
echo $scripts_for_layout;
echo '</head><body>';
// END HTML head ///////////////////////////////////////////////////////////////

// HTML body ///////////////////////////////////////////////////////////////////

// Create a clickable banner.
$banner = $ajax->link($html->image('/img/banner.png',
        array('alt' => 'BuyAndSellOnline', 'style' => 'border:0')),
    '/',
    null, null, false);
// Create div for loading spinner.
$spinner = $html->div('spinner span-1 last',
           $html->image('/img/loading.gif'),
           array('id' => 'spinner', 'style' => 'display:none'));

$header = $html->div('span-23', $banner, array('id' => 'banner')) .
    $spinner;
// Create the menu.
$likeButton = '<fb:like href="http://94.254.42.77/BuyAndSellOnline/" layout="button_count"></fb:like>';
$menu = $html->div('span-3', $this->element('menu') . $likeButton,
        array('id' => 'menuContainer'));

// Create the main content div.
$content = $html->div('span-19', $content_for_layout, array('id' => 'content'));

// List the latest items.
$items = $this->requestAction(
 array('controller' => 'items', 'action' => 'latest'));
$latest = '';
foreach ($items as $item) {
    $this->set('item', $item);
    $latest .= $this->element('item_latest');
}
$latest = $html->div('span-2 last', $html->tag('h4', 'Latest') . $latest,
          array('id' => 'latest'));

// Update latest items automatically.
$updateLatest = $ajax->remoteTimer(
    array('url' => array('controller' => 'items', 'action' => 'latest'),
        'update' => 'news',
        'frequency' => 5,
        'evalScripts' => true));
// Create the footer.
$footer = $html->div('span-24 last',
          '© 2010<br />Robin Axelsson & Henrik Holmberg', array('id' => 'footer'));
// Create div for facebook, required for script to work.
$facebookDiv = $html->div(null, '', array('id' => 'fb-root'));
// Create javascript for loading facebook framework.
$facebookJS = $javascript->link('http://connect.facebook.net/en_US/all.js');
$facebookJS .= $javascript->codeBlock("FB.init({appId: '120588011307924', status: true, cookie: true, xfbml: true});
  FB.Event.subscribe('auth.sessionChange', function(response) {
    if (response.session) {
      // A user has logged in, and a new cookie has been saved
    } else {
      // The user has logged out, and the cookie has been cleared
    }
  });");
$facebookJS .= $javascript->codeBlock("
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
        window.location = '" .
               $html->url(array('controller' => 'users', 'action' => 'login')) . "';
      });") . $javascript->blockEnd();

// Create the container div.
$container = $html->div('container', $header . $menu . $content . $latest .
             $updateLatest . $footer . $facebookDiv . $facebookJS);

echo $container;

echo '</body></html>';
// END HTML body ///////////////////////////////////////////////////////////////

?>