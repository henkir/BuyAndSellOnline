<?php
echo $html->tag('h2', 'Add group');
if ($session->flash()) {
    echo $session->flash();
 }
echo $form->create('Group');
echo $form->input('name', array('label' => 'Name: '));
echo $form->end('Add group');
?>