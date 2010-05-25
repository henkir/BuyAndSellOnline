<?php
echo $html->tag('h2', 'Users');
$paginator->options(array('update' => 'content', 'indicator' => 'spinner'));
echo $ajax->div('users');
// Print sort options
echo $html->div('sort', 'Sort: '.
    $paginator->sort('Username', 'username').' '.
    $paginator->sort('Nickname', 'nickname').' '.
    $paginator->sort('First name', 'firstname').' '.
    $paginator->sort('Last name', 'lastname').' '.
    $paginator->sort('Created', 'created').
    $html->div('spinner', $html->image('/img/loading.gif'),
        array('id' => 'spinner', 'style' => 'display:none')));

$out = '';
foreach ($data as $user) {
    $name = 'N/A';
    if (!empty($user['User']['username'])) {
        $name = $user['User']['username'];
    } elseif (!empty($user['User']['nickname'])) {
        $name = $user['User']['nickname'];
    } elseif (!empty($user['User']['first_name']) && !empty($user['User']['last_name'])) {
        $name = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
    }
    $out .= $html->tag('li',
		       $ajax->link($name,
				   array('action' => 'view',
					 $user['User']['id']),
				   array('update' => 'content')));
}
echo $html->tag('ul', $out, array('class' => 'users'));

echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');

echo $ajax->divEnd('users');
?>