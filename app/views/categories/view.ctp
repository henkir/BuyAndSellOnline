<?php

echo $html->tag('h2', $category['Category']['name']);

$out = '';

foreach ($category['Item'] as $item) {
    $out .= $html->tag('li',
		       $ajax->link($item['name'],
				   array('controller' => 'items',
					 'action' => 'view',
					 $item['id']),
				   array('update' => 'content')));
}

echo $html->tag('ul', $out);

?>