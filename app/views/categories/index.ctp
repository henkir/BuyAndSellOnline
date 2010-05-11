<?php
echo $html->tag('h2', 'Categories');
$out = '';
foreach ($categories as $category) {
    $out .= $html->tag('li',
		       $ajax->link($category['Category']['name'],
				   array('action' => 'view',
					 $category['Category']['id']),
				   array('update' => 'content')));
}
echo $html->tag('ul', $out, array('class' => 'categories'));
?>