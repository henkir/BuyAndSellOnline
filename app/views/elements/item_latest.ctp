<?php

  /**
   * Creates a small version of an Item for displaying in
   * a Latest sidebar.
   */

$click = $ajax->link($item['Item']['name'],
         array('controller' => 'items',
             'action' => 'view',
             $item['Item']['id']),
         array('update' => 'content',
             'indicator' => 'spinner'));
$title = $html->tag('h5', $click);
$image = '';
$description = '';
if (!empty($item['Item']['image'])) {
    $image = $ajax->link($html->image('/img/uploads/' .
                 $item['Item']['image'],
                 array('class' => 'latestImg')),
             array('controller' => 'items',
                 'action' => 'view',
                 $item['Item']['id']),
             array('update' => 'content',
                 'indicator' => 'spinner'),
             null,
             false);
 } else {
    $description = trim(substr($item['Item']['description'], 0, 30));
    if (strlen($item['Item']['description']) > 30) {
        $description .= '...';
    }
 }

echo $html->div('itemLatest', $title . $description . $image,
    array('id' => 'item_latest' . $item['Item']['id'], 'style' => 'float:left'));

?>