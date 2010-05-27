<?php

  /**
   * Prints the latest Items very shortly.
   */

$out = '';
foreach ($items as $item) {

    $this->set('item', $item);
    $this->set('latest', $latest);
    $out .= $this->element('item_latest');

  }

echo $html->div(null, $html->tag('h4', 'Latest') . $out);

?>