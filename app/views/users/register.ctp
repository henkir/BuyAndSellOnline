<?php

  /**
   * Prints a registration form.
   */

echo $html->tag('h2', 'Register');

if ($loggedIn) {
    echo 'You are logged in, so why register?';
 } else {
    echo $html->para(null, 'Fields marked with bold text are required.');
    if ($session->flash()) {
	echo $session->flash();
    }

    // Create form components
    $rForm = $form->create('User', array('class' => 'register', 'action' => 'register'));
    $rUsername = $form->input('username', array('label' => 'Username:'));
    $rPassword = $form->input('passwd', array('label' => 'Password:', 'type' => 'password'));
    $rEmail = $form->input('email', array('label' => 'Email:'));
    $rFirstName = $form->input('first_name', array('label' => 'First name:'));
    $rLastName = $form->input('last_name', array('label' => 'Last name:'));
    $rFocus = $javascript->codeBlock("Form.Element.focus('UserUsername')") .
	$javascript->blockEnd();
    $rSubmit = $html->div(null, $form->label(null, '') .
			  $form->submit('Register', array('div' => false)));
    $rEnd = $form->end();

    echo $rForm . $rUsername . $rPassword . $rEmail .
	$rFirstName . $rLastName . $rFocus . $rSubmit . $rEnd;

 }
?>