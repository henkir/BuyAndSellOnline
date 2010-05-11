<?php

  /**
   * Creates a small version of an Item for displaying in
   * a Latest sidebar.
   */

$click = $ajax->link($item['Item']['name'],
		     array('controller' => 'items',
			   'action' => 'view',
			   $item['Item']['id']),
		     array('update' => 'content'));
echo $html->tag('h5', $click);
if (!empty($item['Item']['image'])) {
    $imageClick = $ajax->link($html->image('/img/uploads/' .
					   $item['Item']['image'],
					   array('class' => 'item_small_img')),
			      array('controller' => 'items',
				    'action' => 'view',
				    $item['Item']['id']),
			      array('update' => 'item'.$item['Item']['id']),
			      null,
			      false);
    echo $imageClick;
 }

?>