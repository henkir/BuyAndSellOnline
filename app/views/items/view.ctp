<?php
$id = $item['Item']['id'];
$name = $item['Item']['name'];
$description = $item['Item']['description'];
$price = $item['Item']['price'];

$click = $ajax->link($item['Item']['name'], array('action' => 'index', $item['Item']['id']), array('update' => 'item'.$item['Item']['id']));

if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link($html->image('/img/uploads/'.$item['Item']['image'], array('class' => 'item_img')), array('action' => 'index', $item['Item']['id']), array('update' => 'item'.$item['Item']['id']), null, false);
    echo $imageClick;
 }
echo $html->tag('h3', $click);
echo $html->tag('span', $time->format('Y-m-d H:i', $item['Item']['created']), array('class' => 'dateCreated'));
if ($item['Item']['modified']) {
    echo $html->tag('span', $item['Item']['modified'], array('class' => 'dateCreated'));
 }
echo $html->para(null, $item['Item']['description']);
echo $html->div(null, $number->currency($item['Item']['price'], 'USD'));
if ($loggedIn) {
    echo '<input type="button" value="Buy" onclick="'.$ajax->remoteFunction(array('url' => array('controller' => 'items', 'action' => 'buy', $id), 'update' => 'content')).'">';
}

?>