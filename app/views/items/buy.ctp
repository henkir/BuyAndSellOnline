<?php

if (isset($confirm)) {
    echo $html->tag('h2', 'Purchase confirmed');
    echo $html->para(null, 'You have bought "' . $item['Item']['name'] . '" from ' .
        $seller['User']['first_name'] . ' ' . $seller['User']['last_name'] .
        ' living in ' . $seller['Country']['name'] . '.');
    echo $html->para(null, 'Do not forget to confirm when you have received the purchased item.');
 } elseif (isset($error)) {
     echo $error;
   } elseif (isset($purchased)) {
       echo $html->tag('h2', 'Bought');
       echo $html->para(null, 'The item has already been purchased.');
     } else {
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

      echo $ajax->submit('Buy', array('url' => array('controller' => 'items',
                                             'action' => 'buy', $item['Item']['id'],
                                             true),
              'update' => 'content',
              'indicator' => 'spinner'));
      echo $form->end();
  }

?>