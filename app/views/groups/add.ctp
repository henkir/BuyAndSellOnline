<h2>Add group</h2>
<?php
echo $form->create('Group');
echo $form->input('name', array('label' => 'Name: '));
echo $form->end('Add group');
?>