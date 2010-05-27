<?php

  /**
   * Prints two forms, one for logging in with OpenID (default),
   * and one for logging in with a username and password.
   */

echo $html->tag('h2', 'Login');
if ($session->flash()) {
    echo $session->flash();
 }

if ($loggedIn) {
    echo 'Already logged in.';
 } else {
    echo '<meta property="og:image" content="http://' . Configure::read('ip') . Configure::read('relativeUrl') . '/img/banner_small.png"/>';
    echo $javascript->codeBlock("
window.fbAsyncInit = function() {
        FB.init({appId: '120588011307924', status: true, cookie: true,
                 xfbml: true});
      };
      (function() {
        var e = document.createElement('script');
        e.type = 'text/javascript';
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());");
    echo $javascript->blockEnd();

    echo $html->para(null, 'You can login using your Facebook account, your OpenID account or the traditional way.');

    echo $html->para('form', 'Login using ' .
        $html->link('Facebook', 'http://www.facebook.com/', array('target' => 'blank')) .
        ' <fb:login-button perms="email,user_about_me"></fb:login-button>');

    // Create OpenID form components
    $oForm = $form->create('User',
             array('type' => 'post', 'action' => 'login',
                 'id' => 'UserLoginFormOpenid',
                 'class' => 'form'));
    $oOpenid = $form->input('OpenidUrl.openid',
               array('label' => false,
			    'div' => false,
			    'class' => 'openidLogin'));
    $oSubmit = $form->submit('Login',
               array('div' => false, 'id' => 'UserLoginSubmitOpenid'));
    $oAuthenticating = $javascript->event('UserLoginFormOpenid',
					  'submit',
					  "Form.Element.setValue('UserLoginSubmitOpenid','Authenticating...');Form.Element.disable('UserLoginSubmitOpenid');");
    $oFocus = $javascript->codeBlock("Form.Element.focus('OpenidUrlOpenid');");
    $oEnd = $form->end();

    // Create traditional login components
    $lForm = $form->create('User', array('type' => 'post',
					 'action' => 'login',
					 'class' => 'login form'));
    $lUsername = $form->input('username',
			      array('label' => 'Username:'));
    $lPassword = $form->input('password',
			      array('label' => 'Password:'));
    $lSubmit = $html->div(null,
			  $form->label(null, '') .
			  $form->submit('Login',
                  array('div' => false, 'id' => 'UserLoginSubmit')));
    $lAuthenticating = $javascript->event('UserLoginForm',
                       'submit',
                       "Form.Element.setValue('UserLoginSubmit','Authenticating...');Form.Element.disable('UserLoginSubmit');");
    $lEnd = $form->end();

    echo $oForm . $oOpenid . $oSubmit . $oAuthenticating . $oFocus . $oEnd .
        $html->para(null,
		    'Don\'t have an <a href="http://openid.net/" target="_blank">OpenID</a>? Get one at <a href="http://www.myid.net/" target="_blank">myID.net</a>.');
    echo $html->div(null,
        'Or, if you want to login in a traditional way.') .
        $html->div(null, $lForm . $lUsername . $lPassword . $lSubmit . $lAuthenticating . $lEnd .
            $html->para(null,
                "Don't have an account? " .
                $ajax->link('Register',
                    array('controller' => 'users',
                        'action' => 'register'),
                    array('update' => 'content')) .
                ' free.'));
 }

?>