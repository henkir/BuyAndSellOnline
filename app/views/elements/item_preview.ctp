<?php

  /**
   * Creates a preview of an Item.
   */

$descriptionLength = 100;

$click = $ajax->link($item['Item']['name'],
         array('controller' => 'items',
             'action' => 'view',
             $item['Item']['id']),
         array('update' => 'item' . $item['Item']['id'],
             'complete' => "Effect.Appear('item" . $item['Item']['id'] ."')"));

// Truncate description if too long.
$description = trim(substr($item['Item']['description'], 0, $descriptionLength));
if (strlen($item['Item']['description']) > $descriptionLength) {
    $description .=  '...';
 }

// If there is an image, create a link and print image.
if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link($html->image('/img/uploads/'.$item['Item']['image'],
                      array('class' => 'itemSmallImg')),
			      array('controller' => 'items',
                      'action' => 'view', $item['Item']['id']),
			      array('update' => 'item'.$item['Item']['id'],
                      'complete' => "Effect.Appear('item" . $item['Item']['id'] ."')"),
			      null,
			      false);
    echo $imageClick;
 }
echo $html->tag('h4', $click);
echo $html->para(null, $description);
echo $html->div(null, $number->currency($item['Item']['price'], 'USD'));



?>
