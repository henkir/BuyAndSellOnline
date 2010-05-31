<?php

  /**
   * Creates a view of an item with all details.
   */

  // Create a clickable title.
$click = $ajax->link($item['Item']['name'],
         array('action' => 'index', $item['Item']['id']),
         array('update' => 'item'.$item['Item']['id']));

// Create a clickable image if any.
if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link(
        $html->image('/img/uploads/' . $item['Item']['image'],
            array('class' => 'itemImg')),
        array('action' => 'index', $item['Item']['id']),
        array('update' => 'item' . $item['Item']['id']), null, false);
    echo $imageClick;
 }
echo $html->tag('h3', $click);
// Get the seller's nickname or full name.
$username = $item['User']['nickname'];
if (empty($username)) {
    $username = $item['User']['first_name'] . ' ' . $item['User']['last_name'];
 }
echo $html->div('seller', 'Seller: ' .$ajax->link($username,
        array('controller' => 'users',
            'action' => 'viewitems', $item['User']['id']),
        array('update' => 'content', 'indicator' => 'spinner')));
$created = $time->format('Y-m-d H:i', $item['Item']['created']);
$modified = '';
// If item was modified after it was created, show it.
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
// Create category link.
$category = $ajax->link($item['Category']['name'],
            array('controller' => 'categories',
                'action' => 'view',
                $item['Category']['id']),
            array('update' => 'content', 'indicator' => 'spinner'));

echo $html->div('itemCreated', 'Created: ' . $created . $modified);
echo $html->div('itemCategory', 'Category: ' . $category);
echo $html->para(null, $item['Item']['description']);
echo $html->div('itemPrice', $number->currency($item['Item']['price'], 'USD'));
// If user is logged in and doesn't own the item, show a buy link.
// If a user is logged in and owns the item, show edit/delete links.
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
                array('update' => 'content', 'indicator' => 'spinner')));
    }
 }

?>