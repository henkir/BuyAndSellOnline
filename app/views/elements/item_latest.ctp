<?php

  /**
   * Creates a small version of an Item for displaying in
   * a Latest sidebar.
   */

$click = $ajax->link($item['Item']['name'],
         array('controller' => 'items',
             'action' => 'view',
             $item['Item']['id']),
         array('update' => 'content'));
$title = $html->tag('h5', $click);
$image = '';
$description = '';
if (!empty($item['Item']['image'])) {
    $image = $ajax->link($html->image('/img/uploads/' .
                 $item['Item']['image'],
                 array('class' => 'latest_img')),
             array('controller' => 'items',
                 'action' => 'view',
                 $item['Item']['id']),
             array('update' => 'content'),
             null,
             false);
 } else {
    $description = trim(substr($item['Item']['description'], 0, 30));
    if (strlen($item['Item']['description']) > 30) {
        $description .= '...';
    }
 }

echo $html->div('item_latest', $title . $description . $image,
    array('id' => 'item_latest' . $item['Item']['id'], 'style' => 'float:left'));
if ((strtotime(date('Y-m-d H:i:s')) - strtotime($item['Item']['created'])) < 9) {
    echo $javascript->codeBlock("new Effect.Highlight('item_latest" .
        $item['Item']['id'] . "', { startcolor: '#6666ee',endcolor: '#ffffff' });");
    echo $javascript->blockEnd();
 }


?>