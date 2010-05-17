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
$created = $time->format('Y-m-d H:i', $item['Item']['created']);
echo $html->tag('span', $created);
if ($item['Item']['modified']) {
    $modified = $time->format('Y-m-d H:i', $item['Item']['modified']);
    if (strtotime($modified) - strtotime($created) > 60) {
        echo $html->tag('span',
            ' ' . $modified);
    }
 }
echo $html->para(null, $item['Item']['description']);
echo $html->div(null, $number->currency($item['Item']['price'], 'USD'));
if ($loggedIn) {
    echo '<input type="button" value="Buy" onclick="' .
        $ajax->remoteFunction(
            array(
                'url' => array('controller' => 'items',
                       'action' => 'buy', $item['Item']['id']),
                'update' => 'content')).'">';
}

?>