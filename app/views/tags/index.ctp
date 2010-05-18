<?php

echo $html->tag('h2', 'Tags');

$paginator->options(array('update' => 'content', 'indicator' => 'spinner'));
echo $ajax->div('tags');
// Print sort options
echo $html->div('sort', 'Sort: '.
    $paginator->sort('Name', 'name').' '.
    $paginator->sort('Created', 'created').
    $html->div('spinner', $html->image('/img/loading.gif'),
        array('id' => 'spinner', 'style' => 'display:none')));

$out = '';
foreach ($data as $tag) {
    $out .= $html->tag('li',
		       $ajax->link($tag['Tag']['name'],
				   array('action' => 'view',
					 $tag['Tag']['id']),
				   array('update' => 'content')));
}
echo $html->tag('ul', $out, array('class' => 'tags'));

echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');

echo $ajax->divEnd('tags');

?>