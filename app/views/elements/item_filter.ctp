<?php

echo $ajax->div('item_filter');
echo $form->create('Item', array('action' => 'index'));
echo $form->input('keyword', array('label' => 'Search: ', 'default' => $session->read('Items.keyword'), 'div' => false));
echo $ajax->submit('Search', array('url' => array('controller' => 'items', 'action' => 'index'), 'update' => 'items', 'div' => false, 'indicator' => 'spinner'));
echo $javascript->codeBlock("Form.Element.focus('ItemKeyword')");
echo $javascript->blockEnd();
echo $form->end();

echo $form->create('Clear', array('url' => array('controller' => 'items', 'action' => 'clear')));
echo $ajax->submit('Clear filters', array('url' => array('controller' => 'items', 'action' => 'clear'), 'update' => 'content', 'indicator' => 'spinner'));
echo $form->end();
echo $ajax->divEnd('item_filter');

?>