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

    echo  $rss->item(array(), array(
				    'title' => $item['Item']['name'],
				    'link' => $itemLink,
				    'guid' => array('url' => $itemLink, 'isPermaLink' => 'true'),
				    'description' =>  $bodyText,
				    'dc:creator' => $item['User']['name'],
				    'pubDate' => $item['Item']['created']));

}

?>