<?php

  // If items is set then print filtered and paginated Items.
if (isset($items)) {
    // Print filter
    echo $this->element('item_filter');
    // Set what to update with paginator links
    $paginator->options(array('update' => 'items', 'indicator' => 'spinner'));
    echo $ajax->div('items');
    // Print sort options
    echo $html->div('sort', 'Sort: '.
		    $paginator->sort('Name', 'name').' '.
		    $paginator->sort('Created', 'created').' '.
		    $paginator->sort('Price', 'price').
		    $html->div('spinner', $html->image('/img/loading.gif'),
			       array('id' => 'spinner', 'style' => 'display:none')));
    // Print all Items.
    foreach ($data as $item) {
	$this->set('item', $item);
	echo $html->div('item', $this->element('item_preview'),
			array('id' => 'item'.$item['Item']['id']));
    }
    // Print the paginator links
    echo $html->div('paginator',
		    $paginator->prev('« Previous').' '.
		    $paginator->numbers(array('url' => array($url))).' '.
		    $paginator->next('Next »').'<br />'.
		    $paginator->counter().' ');

    $relativeUrl = Configure::read('relativeUrl');
    // Right array navigates to next items
    echo $javascript->codeBlock("shortcut.remove('Right');");
    if ($paginator->hasNext()) {
	$next = (string) ((int) $paginator->current() + 1);
	echo $javascript->codeBlock("shortcut.add('Right',function(){new Ajax.Updater('items','" .
				    $relativeUrl . "/items/index/page:" .
				    $next . "',{asynchronous:true,evalScripts:true,onComplete:function(request, json) {Element.hide('spinner');}, onLoading:function(request) {Element.show('spinner');},requestHeaders:['X-Update','items']});});");
    }
    echo $javascript->blockEnd();
    // Left arrow navigates to previous items
    echo $javascript->codeBlock("shortcut.remove('Left');");
    if ($paginator->hasPrevious()) {
	$previous = (string) ((int) $paginator->current() - 1);
	echo $javascript->codeBlock("shortcut.add('Left',function(){new Ajax.Updater('items','" .
				    $relativeUrl . "/items/index/page:" .
				    $previous . "',{asynchronous:true,evalScripts:true,onComplete:function(request, json) {Element.hide('spinner');}, onLoading:function(request) {Element.show('spinner');},requestHeaders:['X-Update','items']});});");
    }
    echo $javascript->blockEnd();
    echo $ajax->divEnd('items');
 } elseif (isset($item)) {
     // If item is set, we should only print that item
     echo $this->element('item_preview', array('item' => $item));
   }


?>
