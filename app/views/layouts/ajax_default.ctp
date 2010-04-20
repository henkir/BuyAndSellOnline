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
                <?php echo $ajax->link('test', array('controller' => 'categories', 'action' => 'index'), array('update' => 'content')); ?>
                <div><a href="">Home</a></div>
                <div><a href="">Search</a></div>
                <div><a href="">Filter</a></div>
                <div><a href="">Login</a></div>
                <div><a href="">Profile</a></div>
                <div><a href="">Sell</a></div>
            </div>
            <div id="content" class="span-20 last">
                <?php echo $content_for_layout; ?>

            </div>
            <div id="footer" class="span-24 last"></div>
        </div>
    </body>
</html>
