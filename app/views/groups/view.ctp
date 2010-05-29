<?php

echo $html->tag('h2', $group['Group']['name']);
if ($session->flash()) {
    echo $session->flash();
 }
$out = '';

foreach ($group['User'] as $user) {
    $link = $ajax->link($user['username'],
			array('controller' => 'users',
			      'action' => 'view',
			      $user['id']));
    $out .= $html->tag('li', $link, null, false);
}

echo $html->tag('ul', $out);

?>