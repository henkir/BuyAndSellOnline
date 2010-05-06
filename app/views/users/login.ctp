<?php
if (isset($message)) {
    echo '<p class="error">'.$message.'</p>';
 }
if ($loggedIn) {
    echo 'Already logged in.';
 } else {
    echo $form->create('User', array('type' => 'post', 'action' => 'login'));
    echo $form->input('OpenidUrl.openid',
		      array('label' => false,
			    'div' => false,
			    'class' => 'loginTextbox'));
    echo $form->submit('Login', array('div' => false));
    echo $javascript->codeBlock('Form.Element.focus(\'OpenidUrlOpenid\');');
    echo $form->end();
    echo $html->div('', 'Don\'t have an <a href="http://openid.net/" target="_blank">OpenID</a>? Get one at <a href="https://www.myopenid.com/" target="_blank">myOpenID</a>.');
 }

?>