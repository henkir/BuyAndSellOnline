<?php

echo $html->tag('h2', $tag['Tag']['name']);

$itemsList = '';

foreach($tag['Item'] as $item) {
    $itemsList .= $html->tag('li',
                  $ajax->link($item['name'],
                      array('controller' => 'items',
                          'action' => 'view',
                          $item['id']),
                      array('update' => 'content')));
}

echo $html->tag('ul', $itemsList);

?>