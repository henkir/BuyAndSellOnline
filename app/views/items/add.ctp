<?php

echo $form->create('Item', array('enctype' => 'multipart/form-data'));
echo $form->input('name', array('label' => 'Title: ', 'maxLength' => 20));
// TODO: Nicer way to get a list of categories??? MUST BE
$categories = array();
$i = 0;
foreach ($items as $item) {
    $categories[$i++] = $item['Category'];
}
$categories = $items;

$options = Set::combine($categories, '{n}.Category.id', '{n}.Category.name');
echo $form->label('category_id', 'Category: ');
echo $form->select('category_id', $options);
echo $form->input('price', array('label' => array('Price: ')));
echo $form->input('paypal', array('label' => 'Paypal account: '));
echo $form->label('picture', 'Picture: ');
echo $form->file('picture');
echo $form->input('description', array('label' => array('style' => 'vertical-align:top', 'text' => 'Description: ')));
echo $ajax->submit('Add');
echo $javascript->codeBlock('Form.Element.focus(\'ItemName\');');
echo $form->end();

?>