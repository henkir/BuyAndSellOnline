<?php

if (isset($data)) {

    $name = $user['User']['nickname'];
    if (empty($name)) {
        $name = $user['User']['fullname'];
    }
    if ($name{strlen($name)-1} == 's') {
        $name .= "'";
    } else {
        $name .= "'s";
    }
    $name .= ' items';
    echo $html->tag('h2', $name);

    $paginator->options(array('update' => 'content', 'indicator' => 'spinner',
            'url' => array('controller' => 'users', 'action' => 'items',
                   $user['User']['id'])));
    echo $ajax->div('items');
    // Print sort options
    echo $html->div('sort', 'Sort: '.
        $paginator->sort('Name', 'name').' '.
        $paginator->sort('Created', 'created').' '.
        $paginator->sort('Price', 'price'));

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

 }

?>