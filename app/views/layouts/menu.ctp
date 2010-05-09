<?php

  /**
   * Prints the menu which depends on whether the user is logged in or not, is moderator or not and is admin or not.
   */

// TODO: check if a logged in user is a moderator
$moderator = $loggedIn;
$admin = $moderator;

$this->set('loggedIn', $loggedIn);
$this->set('moderator', $moderator);
$this->set('admin', $admin);

echo $this->element('menu');
?>