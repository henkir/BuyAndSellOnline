<?php

$this->set('documentData', array(
				 'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));

$this->set('channelData', array(
        'title' => __("BuyAndSellOnline Most Recent Items", true),
        'link' => $html->url('/', true),
        'description' => __("Most recent items on BuyAndSellOnline.", true),
        'language' => 'en-us'));

foreach ($items as $item) {
    $itemLink = array('controller' => 'items',
		      'action' => 'view',
		      $item['Item']['id']);

    App::import('Sanitize');

    $bodyText = preg_replace('=\(.*?\)=is', '', $item['Item']['description']);
    $bodyText = $text->stripLinks($bodyText);
    $bodyText = Sanitize::stripAll($bodyText);
    $bodyText = $text->truncate($bodyText, 400, '...', true, true);

    $name = 'N/A';
    if (!empty($item['User']['username'])) {
        $name = $item['User']['username'];
    } elseif (!empty($item['User']['nickname'])) {
        $name = $item['User']['nickname'];
    } elseif (!empty($item['User']['first_name'])
        && !empty($item['User']['last_name'])) {
        $name = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
    }

    echo $rss->item(array(), array(
            'title' => $name,
            'link' => $itemLink,
            'guid' => array('url' => $itemLink, 'isPermaLink' => 'true'),
            'description' =>  $bodyText,
            'dc:creator' => $name,
            'pubDate' => $item['Item']['created']));

}


?>