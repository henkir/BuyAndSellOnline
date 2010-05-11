<?php
echo $html->tag('h2', 'Add Category');
echo $html->para(null, 'Fields marked with bold text are required.');
if ($session->flash()) {
    echo $session->flash();
 }
echo $form->create('Category');
echo $form->input('name', array('label' => array('class' => 'span-4 form',
						 'text' => 'Name: ')));
echo $form->input('category', array('label' => array('class' => 'span-4 form',
						     'text' => 'Parent: '),
				    'empty' => 'None'));
echo $form->label(null, '', array('class' => 'span-4'));
echo $ajax->submit('Add Category',
		   array('url' => array('controller' => 'categories',
					'action' => 'add'),
			 'update' => 'content'));
echo $javascript->codeBlock("Form.Element.focus('CategoryName');");
echo $javascript->blockEnd();
echo $form->end();
?>