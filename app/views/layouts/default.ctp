<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title><?php echo $title_for_layout; ?></title>
        <link rel="shortcut icon" href="/BuyAndSellOnline/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/BuyAndSellOnline/css/blueprint/screen.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="/BuyAndSellOnline/css/blueprint/print.css" type="text/css" media="print" />	
        <!--[if lt IE 8]><link rel="stylesheet" href="/BuyAndSellOnline/css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
        <link rel="stylesheet" href="/BuyAndSellOnline/css/buyandsellonline.css" type="text/css" media="screen, projection" />
        <?php echo $scripts_for_layout ?>

    </head>
    
    <body>
        <div class="container">
            <div id="header" class="span-24 last">
                <div id="banner" class="span-24 last">
                    <a href="/"><img src="img/banner.png" alt="BuyAndSellOnline" style="border:0" /></a>
                </div>
            </div>
            <div id="menu" class="span-4">
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
