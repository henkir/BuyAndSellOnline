<?php

$click = $ajax->link($item['Item']['name'],
         array('action' => 'index', $item['Item']['id']),
         array('update' => 'item'.$item['Item']['id']));

if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link(
        $html->image('/img/uploads/' . $item['Item']['image'],
            array('class' => 'item_img')),
        array('action' => 'index', $item['Item']['id']),
        array('update' => 'item' . $item['Item']['id']), null, false);
    echo $imageClick;
 }
echo $html->tag('h3', $click);
$user = $item['User']['nickname'];
if (empty($user)) {
    $user = $item['User']['fullname'];
 }
echo $html->div('seller', 'Seller: ' .$ajax->link($user,
        array('controller' => 'users',
            'action' => 'items', $item['User']['id']),
        array('update' => 'content')));
$created = $time->format('Y-m-d H:i', $item['Item']['created']);
$modified = '';
if ($item['Item']['modified']) {
    $modified = $time->format('Y-m-d H:i', $item['Item']['modified']);
    if (strtotime($modified) - strtotime($created) < 60) {
        $modified = '';
        echo $html->tag('span',
            ' ' . $modified);
    } else {
        $modified = ' Modified: ' . $modified;
    }
 }

$category = $ajax->link($item['Category']['name'],
            array('controller' => 'categories',
                'action' => 'view',
                $item['Category']['id']),
            array('update' => 'content'));

echo $html->div(null, 'Created: ' . $created . $modified);
echo $html->div(null, 'Category: ' . $category);
echo $html->para(null, $item['Item']['description']);
echo $html->div(null, $number->currency($item['Item']['price'], 'USD'));
if ($loggedIn) {
    if ($item['Item']['user_id'] == $session->read('Auth.User.id')) {
        echo $html->para(null, $ajax->link('Edit', array('controller' => 'items',
                    'action' => 'edit', $item['Item']['id']),
                array('update' => 'content')) . ' ' .
            $ajax->link('Delete', array('controller' => 'items',
                    'action' => 'delete', $item['Item']['id']),
                array('update' => 'content',
                    'confirm' => 'Delete ' . $item['Item']['name'] . '?')));
    } else {
        echo $html->para(null, $ajax->link('Buy', array('controller' => 'items',
                    'action' => 'buy', $item['Item']['id']),
                array('update' => 'content')));
    }
 }

?>