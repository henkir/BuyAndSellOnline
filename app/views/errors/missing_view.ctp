<?php

echo $html->div('error', $html->tag('h2', '404') . $html->para(null, 'The page you requested does not exist. Check your URL. If it is a broken link that brought you here, please contact the site administrator.'));

$updateMenu = $javascript->codeBlock('new Ajax.Updater("menu","' .
              $html->url(array('controller' => 'layouts', 'action' => 'menu')) .
              '",{method:"get",evalScripts:true});') .
    $javascript->blockEnd();

echo $updateMenu;

?>