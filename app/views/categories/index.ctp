<?php
echo $html->tag('h2', 'Categories');
if ($session->flash()) {
    echo $session->flash();
 }
$paginator->options(array('update' => 'content', 'indicator' => 'spinner'));
echo $ajax->div('categories');
// Print sort options
echo $html->div('sort', 'Sort: '.
    $paginator->sort('Name', 'name').' '.
    $paginator->sort('Created', 'created'));

$out = '';
// Create a link for each category.
foreach ($data as $category) {
    $out .= $html->tag('li',
		       $ajax->link($category['Category']['name'],
				   array('action' => 'view',
					 $category['Category']['id']),
				   array('update' => 'content', 'indicator' => 'spinner')));
}
echo $html->tag('ul', $out, array('class' => 'categories'));

echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');

echo $ajax->divEnd('categories');
?>