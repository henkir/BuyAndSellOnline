<?php

echo $html->tag('h2', 'Users');
if (isset($category)) {
    echo $ajax->link('User list',
		     array('action' => 'edit'),
		     array('update' => 'content'));
    echo $html->para(null, 'Fields marked with bold text are required.');
 }

if ($session->flash()) {
    echo $session->flash();
 }

if (isset($user)) {
    echo $form->create('User');
    if ($session->read('Auth.User.id') != $user['User']['id']) {
        echo $form->input('groups',
            array('label' => array('class' => 'span-4 form',
                           'text' => 'Group:'),
			    'default' => $user['User']['group_id']));
    }
    echo $ajax->submit('Save User',
		       array('url' => array('controller' => 'users',
					    'action' => 'edit',
                            $user['User']['id']),
                   'update' => 'content'));
    echo $javascript->codeBlock("Form.Element.focus('UserGroups');");
    echo $javascript->blockEnd();
    echo $form->end();
 } else {
    $paginator->options(array('update' => 'content', 'indicator' => 'spinner'));
    // Show list of all categories, with delete links
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
        } elseif (!empty($user['User']['first_name'])
            && !empty($user['User']['last_name'])) {
            $name = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
        }
        $out .=	$html->tag('li',
                $html->tag('span', $user['User']['id'] . ' ' . $name).
                $ajax->link($html->image('edit.gif',
                        array('alt' => 'Edit',
                            'class' => 'edit')),
                    array('action' => 'edit',
                        'id' => $user['User']['id']),
                    array('update' => 'content'),
                    null,
                    false).' '.
                $ajax->link($html->image('delete.png',
                        array('alt' => 'Delete',
                            'class' => 'delete')),
                    array('action' => 'delete',
                        'id' => $user['User']['id']),
                    array('update' => 'content'),
                    'Delete '.$name.'?',
                    false));
    }
    echo $html->tag('ul', $out, array('class' => 'editList'));

    echo $html->div('paginator',
        $paginator->prev('« Previous').' '.
        $paginator->numbers().' '.
        $paginator->next('Next »').'<br />'.
        $paginator->counter().' ');
 }

?>