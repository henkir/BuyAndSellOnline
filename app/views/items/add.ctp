<?php

  /**
   * This form cannot be AJAX since file uploads aren't permitted through
   * AJAX because of security reasons, and the user is permitted to upload
   * an image.
   */

echo $html->tag('h2', 'Add Item');
echo $html->para(null, 'Fields marked with bold text are required. The rest are not required, but recommended.');
if ($session->flash()) {
    echo $session->flash();
 }
echo $form->create('Item', array('enctype' => 'multipart/form-data', 'class' => 'addItem'));
echo $form->input('name', array('label' => 'Title: '));
//$options = Set::combine($categories, '{n}.Category.id', '{n}.Category.name');
//echo $form->label('category_id', 'Category: ');
//echo $form->select('category_id', $options);
echo $form->input('categories');
//echo $form->create('Tag', array('url' => array('controller' => 'tags', 'action' => 'add')));
//echo $form->end('Submit');
echo $form->input('price', array('label' => 'Price: '));
echo $form->input('paypal', array('label' => 'Paypal account: '));
echo $form->input('file', array('type' => 'file', 'label' => 'Image: ', 'name' => 'file'));
echo $form->input('description', array('label' => array('style' => 'vertical-align:top', 'text' => 'Description: ')));
echo $form->input('Tag', array('label' => array('style' => 'vertical-align:top', 'text' => 'Tags: ')));
//echo $form->label('agreed', 'I agree to the '.$html->link('terms of use', array('controller' => 'items', 'action' => 'terms'), array('target' => '_blank')).'.');
echo $form->input('agreed', array('label' => 'I agree to the '.$html->link('terms of use', array('controller' => 'items', 'action' => 'terms'), array('target' => '_blank')).':'));
echo $javascript->codeBlock("Form.Element.focus('ItemName');");
echo $javascript->blockEnd();
echo $form->end('Add');

?>