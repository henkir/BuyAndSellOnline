<?php

  /**
   * Prints the menu which depends on whether the user is logged in or not, is moderator or not and is admin or not.
   */

if (isset($userId)) {
    $this->set('userId', $userId);
 }
echo $this->element('menu');
?>