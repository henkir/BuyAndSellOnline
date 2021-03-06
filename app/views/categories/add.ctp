<?php
echo $html->tag('h2', 'Add Category');
if ($session->flash()) {
    echo $session->flash();
 }
echo $html->para(null, 'Fields marked with bold text are required.');
if ($session->flash()) {
    echo $session->flash();
 }
// Create form.
echo $form->create('Category');
echo $form->input('name', array('label' => array('class' => 'span-4 form',
						 'text' => 'Name: ')));
echo $form->input('category', array('label' => array('class' => 'span-4 form',
						     'text' => 'Parent: '),
				    'empty' => 'None'));
echo $form->label(null, '', array('class' => 'span-4'));
// Use ajax submission.
echo $ajax->submit('Add Category',
		   array('url' => array('controller' => 'categories',
					'action' => 'add'),
			 'update' => 'content'));
// Set focus to the first element.
echo $javascript->codeBlock("Form.Element.focus('CategoryName');");
echo $javascript->blockEnd();
echo $form->end();
?>