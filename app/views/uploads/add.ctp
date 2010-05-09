<?php
echo $session->flash();
if (isset($image)) {
    echo $html->image('/images/'.$image);
 }

echo $form->create('Upload', array('type' => 'file'));
echo $form->input('file', array('type' => 'file'));
echo $form->end('Submit');

?>