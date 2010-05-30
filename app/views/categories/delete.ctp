<?php
if ($session->flash()) {
    echo $session->flash();
 }
echo $html->tag('h2', 'Delete category');
echo $form->create('Category');
$options = Set::combine($categories, '{n}.Category.id', '{n}.Category.name');
echo $form->select('category_id', $options);
echo $form->end('Delete category');
?>