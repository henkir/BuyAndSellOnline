<?php

if (isset($confirm)) {
    // Purchase has been confirmed.
    echo $html->tag('h2', 'Purchase confirmed');
    if ($session->flash()) {
        echo $session->flash();
    }
    echo $html->para('success', 'You have bought "' . $item['Item']['name'] . '" from ' .
        $seller['User']['first_name'] . ' ' . $seller['User']['last_name'] .
        ' living in ' . $seller['Country']['name'] . '.');
    echo $html->para(null, 'Do not forget to confirm when you have received the purchased item.');
 } elseif (isset($error)) {
     echo $error;
   } elseif (isset($purchased)) {
       // Item has already been purchased.
       echo $html->tag('h2', 'Purchased');
       echo $html->para(null, 'The item has already been purchased.');
     } else {
      // Create the form for getting all payment information.
      echo $html->tag('h2', 'Buy ' . $item['Item']['name']);
      echo $form->create('User', array('class' => 'addItem',
              'url' => array('controller' => 'items',
                     'action' => 'buy', $item['Item']['id'],
                     true)));
      echo $form->input('first_name', array('label' => 'First name:',
              'default' => $user['User']['first_name']));
      echo $form->input('last_name', array('label' => 'Last name:',
              'default' => $user['User']['last_name']));
      echo $form->input('address', array('label' => 'Address:',
              'default' => $user['User']['address']));
      echo $form->input('zip', array('label' => 'Zip code:',
              'default' => $user['User']['zip']));
      echo $form->input('city', array('label' => 'City:',
              'default' => $user['User']['city']));
      echo $form->input('countries', array('label' => 'Country:',
              'default' => $user['User']['country_id'],
              'empty' => '(choose one)'));
      echo $form->input('creditcard', array('label' => 'Creditcard:',
	      'default' => $user['User']['creditcard']));
      echo $form->input('cvv', array('label' => 'CVV:',
	      'default' => $user['User']['cvv']));
      echo $form->input('expyear', array('label' => 'Expires Year:',
	      'default' => $user['User']['year']));
      echo $form->input('expmonth', array('label' => 'Expires Month:',
	      'default' => $user['User']['month']));

      echo $ajax->submit('Buy', array('url' => array('controller' => 'items',
                                             'action' => 'buy', $item['Item']['id'],
                                             true),
              'update' => 'content',
              'indicator' => 'spinner'));
      echo $form->end();
  }

?>