<?php

  /**
   * This form cannot be AJAX since file uploads aren't permitted through
   * AJAX because of security reasons, and the user is permitted to upload
   * an image.
   */

echo $html->tag('h2', 'Add Item');
echo $html->para(null,
		 'Fields marked with bold text are required. The rest are not required, but recommended.');
if ($session->flash()) {
    echo $session->flash();
 }

// Create form components
$iForm = $form->create('Item', array('enctype' => 'multipart/form-data', 'class' => 'addItem'));
$iName = $form->input('name', array('label' => 'Title:'));
$iCategory = $form->input('categories', array('label' => 'Category:'));
$iPrice = $form->input('price', array('label' => 'Price:'));
$iPaypal = $form->input('paypal', array('label' => 'Paypal account:'));
$iImage = $form->input('file', array('type' => 'file', 'label' => 'Image:', 'name' => 'file'));
$iDescription = $form->input('description', array('label' => array('style' => 'vertical-align:top',
								   'text' => 'Description:')));
$iTag = $form->input('Tag', array('label' => array('style' => 'vertical-align:top',
						   'text' => 'Tags:')));
$iAgreed = $form->input('agreed', array('label' => 'I agree to the ' .
					$html->link('terms',
						    array('controller' => 'items',
							  'action' => 'terms'),
						    array('target' => '_blank')) .
					':'));
// Set focus to name textbox
$iSetFocus = $javascript->codeBlock("Form.Element.focus('ItemName')") .
    $javascript->blockEnd();
$iSubmit = $html->div(null, $form->label(null, '') .
		      $form->end('Add Item'));

// Print form components
echo $iForm . $iName . $iCategory . $iPrice . $iPaypal . $iImage . $iDescription .
$iTag . $iAgreed . $iSetFocus . $iSubmit;

?>