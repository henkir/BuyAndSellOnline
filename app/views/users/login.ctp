<?php
if ($session->flash()) {
    echo $session->flash();
 }

echo $html->tag('h2', 'Login');
if ($loggedIn) {
    echo 'Already logged in.';
 } else {
    echo $form->create('User', array('type' => 'post', 'action' => 'login'));
    echo $form->input('OpenidUrl.openid',
		      array('label' => false,
			    'div' => false,
			    'class' => 'loginTextbox'));
    echo $form->submit('Login', array('div' => false, 'id' => 'UserLoginSubmit'));
    echo $javascript->event('UserLoginForm', 'submit', "Form.Element.setValue('UserLoginSubmit','Authenticating...');Form.Element.disable('UserLoginSubmit');");
    echo $javascript->codeBlock("Form.Element.focus('OpenidUrlOpenid');");
    echo $form->end();
    echo $html->div(null, 'Don\'t have an <a href="http://openid.net/" target="_blank">OpenID</a>? Get one at <a href="https://www.myopenid.com/" target="_blank">myOpenID</a>.');
 }

?>