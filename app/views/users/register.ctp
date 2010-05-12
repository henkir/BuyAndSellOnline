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
    $rForm = $form->create('User',
             array('class' => 'register', 'action' => 'register'));
    $rUsername = $form->input('username',
                 array('label' => 'Username:',
                 'onchange' =>
                     "if (Form.Element.getValue('UserNickname') == '') { Form.Element.setValue('UserNickname', Form.Element.getValue('UserUsername')); }"));
    $rPassword = $form->input('passwd',
                 array('label' => 'Password:', 'type' => 'password'));
    $rEmail = $form->input('email', array('label' => 'Email:'));
    $rFullName = $form->input('fullname', array('label' => 'Full name:'));
    $rNickName = $form->input('nickname', array('label' => 'Nickname:'));
    $rFocus = $javascript->codeBlock("Form.Element.focus('UserUsername')") .
	$javascript->blockEnd();
    $rSubmit = $html->div(null, $form->label(null, '') .
			  $form->submit('Register', array('div' => false)));
    $rEnd = $form->end();

    echo $rForm . $rUsername . $rPassword . $rEmail .
	$rFullName . $rNickName . $rFocus . $rSubmit . $rEnd;

 }
?>