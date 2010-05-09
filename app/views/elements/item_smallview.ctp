<?php

Configure::write('debug', 0);

$descriptionLength = 5;

$click = $ajax->link($item['Item']['name'], array('action' => 'view', $item['Item']['id']), array('update' => 'item'.$item['Item']['id']));

$description = trim(substr($item['Item']['description'], 0, $descriptionLength));
if (strlen($item['Item']['description']) > $descriptionLength) {
    $description .=  '...';
}


echo $html->tag('h4', $click);
echo $html->para(null, $description);
echo $html->div(null, '$'.$item['Item']['price']);


?>
