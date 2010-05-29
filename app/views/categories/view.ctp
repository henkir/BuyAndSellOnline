<?php

$paginator->options(array('update' => 'content', 'indicator' => 'spinner',
        'url' => array('action' => 'view', $category['Category']['id'])));

echo $html->tag('h2', $category['Category']['name']);
if ($session->flash()) {
    echo $session->flash();
 }
echo $ajax->div('items');

echo $html->div('sort', 'Sort: '.
        $paginator->sort('Name', 'name').' '.
        $paginator->sort('Created', 'created').' '.
        $paginator->sort('Price', 'price').
        $html->div('spinner', $html->image('/img/loading.gif'),
        array('id' => 'spinner', 'style' => 'display:none')));

foreach ($data as $item) {
    $this->set('item', $item);
    echo $html->div('item', $this->element('item_preview'),
        array('id' => 'item'.$item['Item']['id']));
}

echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');

echo $ajax->divEnd('items');

?>