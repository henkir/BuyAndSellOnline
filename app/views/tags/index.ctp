<?php

echo $html->tag('h2', 'Tags');

$tagsList = '';
foreach($tags as $tag) {
    $tagsList .= $html->tag('li',
                 $ajax->link($tag['Tag']['name'],
                     array('controller' => 'tags',
                         'action' => 'view',
                         $tag['Tag']['id']),
                     array('update' => 'content')));
}

echo $html->tag('ul', $tagsList);

?>