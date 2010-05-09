<?php

Configure::write('debug', 0);

$descriptionLength = 100;

$click = $ajax->link($item['Item']['name'], array('action' => 'view', $item['Item']['id']), array('update' => 'item'.$item['Item']['id']));


$description = trim(substr($item['Item']['description'], 0, $descriptionLength));
if (strlen($item['Item']['description']) > $descriptionLength) {
    $description .=  '...';
}

if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link($html->image('/img/uploads/'.$item['Item']['image'], array('class' => 'item_small_img')), array('action' => 'view', $item['Item']['id']), array('update' => 'item'.$item['Item']['id']), null, false);
    echo $imageClick;
 }
echo $html->tag('h4', $click);
echo $html->para(null, $description);
echo $html->div(null, '$'.$item['Item']['price']);


?>
