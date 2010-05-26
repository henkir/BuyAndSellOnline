<?php

echo $html->tag('My Purchases');
if ($session->flash()) {
    echo $session->flash();
 }
$out = '';
foreach ($data as $purchase) {
    if (!$purchase['Purchase']['confirmed']) {
        $confirm = $ajax->link('Confirm',
                   array('controller' => 'purchases', 'action' => 'confirm',
                       $purchase['Purchase']['id']),
                   array('update' => 'content',
                       'confirm' =>
                       'Confirm ' . $purchase['Item']['name'] . '?'));
    } else {
        $confirm = 'Confirmed';
    }
    $out .= $html->tag('li',
            $html->tag('span', $purchase['Item']['name']) . $confirm);
}

echo $html->tag('ul', $out, array('class' => 'editList'));

?>