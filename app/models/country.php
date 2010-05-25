<?php

class Country extends AppModel {
    var $name = 'Country';
    var $hasMany = array('User');

  }

?>