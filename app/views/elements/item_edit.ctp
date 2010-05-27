<?php

  /**
   * This form cannot be AJAX since file uploads aren't permitted through
   * AJAX because of security reasons, and the user is permitted to upload
   * an image.
   */

if (isset($item)) {

    echo $html->tag('h2', 'Edit Item');
    echo $html->para(null,
        'Fields marked with bold text are required. The rest are not required, but recommended.');
    if ($session->flash()) {
        echo $session->flash();
    }

    // Create form components
    $iForm = $form->create('Item',
             array('enctype' => 'multipart/form-data', 'class' => 'addItem',
                 'action' => 'edit'));
    $iId = $form->input('id', array('type' => 'hidden'));
    $iName = $form->input('name', array('label' => 'Title:',
             'class' => 'required validate-alphanum'));
    $iCategory = $form->input('categories', array('label' => 'Category:',
                     'empty' => '(choose one)',
                     'default' => $item['Item']['category_id'],
                     'class' => 'required validate-select'));
    $iPrice = $form->input('price', array('label' => 'Price:',
              'class' => 'required validate-currency-dollar'));
    $iPaypal = $form->input('paypal', array('label' => 'Paypal account:',
               'class' => 'required validate-email'));
    $iImage = $form->input('file',
              array('type' => 'file',
                  'label' => array('style' => 'font-weight:normal',
                           'text' => 'Image:'),
                  'name' => 'file'));
    $iDescription = $form->input('description',
                    array('label' => array('style' => 'vertical-align:top',
                                   'text' => 'Description:'),
                    'class' => 'required'));
    $iTag = $form->input('Tag',
            array('label' =>
                array('style' => 'vertical-align:top;font-weight:normal',
                    'text' => 'Tags:')));

    $iAgreed = $form->input('agreed', array('label' => 'I agree to the ' .
                   $html->link('terms',
                       array('controller' => 'items',
                           'action' => 'terms'),
                       array('target' => '_blank')) .
                   ':',
               'class' => 'required'));
    // Set focus to name textbox
    $iSetFocus = $javascript->codeBlock("Form.Element.focus('ItemName');
var valid = new Validation('ItemEditForm', { immediate:true });") .
        $javascript->blockEnd();
    $iSubmit = $html->div(null, $form->label(null, '') .
               $form->end('Save Item'));

    // Print form components
    echo $iForm . $iId . $iName . $iCategory . $iPrice . $iPaypal . $iImage .
        $iDescription . /*$iTag .*/ $iAgreed . $iSetFocus . $iSubmit;

 } else {
    echo 'bla';
 }

?>