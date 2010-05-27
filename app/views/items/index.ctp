<?php

  // If items is set then print filtered and paginated Items.
if (isset($data)) {
    // Print filter
    echo $this->element('item_filter');
    // Set what to update with paginator links
    $paginator->options(array('update' => 'content', 'indicator' => 'spinner'));
    echo $ajax->div('items');
    // Print sort options
    echo $html->div('sort', 'Sort: '.
        $paginator->sort('Name', 'name').' '.
        $paginator->sort('Created', 'created').' '.
        $paginator->sort('Price', 'price')//.
        //$html->div('spinner', $html->image('/img/loading.gif'),
        /*array('id' => 'spinner', 'style' => 'display:none'))*/);
    // Print all Items.
    foreach ($data as $item) {
        $this->set('item', $item);
        echo $html->div('item', $this->element('item_preview'),
			array('id' => 'item'.$item['Item']['id']));
        echo $javascript->blockEnd();
    }
    // Print the paginator links
    echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');

    $relativeUrl = Configure::read('relativeUrl');

    echo $javascript->blockEnd();
    echo $ajax->divEnd('items');
 } elseif (isset($item)) {
     // If item is set, we should only print that item
     echo $this->element('item_preview', array('item' => $item));
   }


?>
