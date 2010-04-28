<?php
// If you have placed the directory like this /path/to/www/BuyAndSellOnline,
// then you put 'BuyAndSellOnline' here. If you placed it so the index.php from
// the /BuyAndSellOnline directory is in the www root, you just enter an empty
// string here.
$relativeUrl = '/BuyAndSellOnline';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title><?php echo $title_for_layout; ?></title>
        <link rel="shortcut icon" href="<?php echo $relativeUrl; ?>/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo $relativeUrl; ?>/css/blueprint/screen.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo $relativeUrl; ?>/css/blueprint/print.css" type="text/css" media="print" />	
        <!--[if lt IE 8]><link rel="stylesheet" href="<?php echo $relativeUrl; ?>/css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
        <link rel="stylesheet" href="<?php echo $relativeUrl; ?>/css/buyandsellonline.css" type="text/css" media="screen, projection" />
        <script type="text/javascript" src="<?php echo $relativeUrl; ?>/js/prototype.js"></script>
        <script type="text/javascript" src="<?php echo $relativeUrl; ?>/js/scriptaculous.js"></script>
        <?php echo $scripts_for_layout ?>

    </head>
    
    <body>
        <div class="container">
            <div id="header" class="span-24 last">
                <div id="banner" class="span-24 last">
                    <a href="<?php echo $relativeUrl; ?>/"><img src="<?php echo $relativeUrl; ?>/img/banner.png" alt="BuyAndSellOnline" style="border:0" /></a>
                </div>
            </div>
            <div id="menu" class="span-4">
	    	<ul>
<?php
echo '<li>', $ajax->link('Home', array('controller' => 'pages', 'action' => 'display'), array('update' => 'content')), '</li>';
echo '<li>', $ajax->link('Browse', array('controller' => 'items', 'action' => 'index'), array('update' => 'content')), '</li>';
echo '<li>', $ajax->link('Search', array('controller' => 'pages', 'action' => 'display'), array('update' => 'content')), '</li>';

if ($session->read('Auth.User.id')) {
   // User logged in
 
   if (true /* is_moderator($session->read('Auth.User.id')) */) {
      // User is moderator
      if (true /* is_admin($session->read('Auth.User.id')) */) {
      	 // User is administrator
	 echo '<li>', $ajax->link('Admin panel', array('controller' => 'pages', 'action' => 'display'), array('update' => 'content')), '</li>';
      }
   }
   echo '<li>', $ajax->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('update' => 'content')), '</li>';
} else {
   echo '<li>', $ajax->link('Login', array('controller' => 'users', 'action' => 'login'), array('update' => 'content')), '</li>';
}
?>
            </div>
            <div id="content" class="span-20 last">
                <?php echo $content_for_layout; ?>
            </div>
            <div id="footer" class="span-24 last"></div>
        </div>
    </body>
</html>
