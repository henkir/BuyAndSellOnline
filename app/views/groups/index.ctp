<?php
echo $html->tag('h2', 'Groups');
if ($session->flash()) {
    echo $session->flash();
 }
$out = '';
// Prints all groups.
foreach ($groups as $group) {
    $out .= $html->tag('li',
		       $ajax->link($group['Group']['name'],
				   array('action' => 'view',
					 $group['Group']['id']),
				   array('update' => 'content')));
}
echo $html->tag('ul', $out, array('class' => 'groups'));
?>