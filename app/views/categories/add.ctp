<?php echo $session->flash(); ?>
<h2>Add category</h2>
<?php
echo $form->create('Category');
echo $form->input('name');
$options = Set::combine($categories, '{n}.Category.id', '{n}.Category.name');
echo $form->select('category_id', $options);
echo $form->end('Add category');
?>