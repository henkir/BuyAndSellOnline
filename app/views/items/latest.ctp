<?php

  /**
   * Prints the latest Items very shortly.
   */

echo $html->tag('h4', 'Latest');

foreach ($items as $item) {

    $this->set('item', $item);
    echo $this->element('item_latest');

  }

?>