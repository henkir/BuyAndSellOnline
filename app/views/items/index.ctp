
<div id="items">
<?php

if (isset($items)) {

    foreach ($items as $item) {
	$id = $item['Item']['id'];
 
	$click = $ajax->link($item['Item']['name'], array('action' => 'view', $id), array('update' => 'item'.$id));
?>
    <div id="item<?php
            echo $id;
            ?>">
        <h4><?php
            echo $click;
            ?></h4>
        <p>
    <?php
    $description = substr($item['Item']['description'], 0, 5);
    if (strlen($item['Item']['description']) > 5) {
        $description .= '...';
    }
    echo $description;
    ?>
        </p>
        <p><?php
	      echo $item['Item']['price'];
    ?></p>
    </div>
    <?php
    }
 } else if (isset($item)) {
    $id = $item['Item']['id'];
 
    $click = $ajax->link($item['Item']['name'], array('action' => 'view', $id), array('update' => 'item'.$id));
?>
    <div id="item<?php
            echo $id;
            ?>">
        <h4><?php
            echo $click;
            ?></h4>
        <p>
    <?php
    $description = substr($item['Item']['description'], 0, 5);
    if (strlen($item['Item']['description']) > 5) {
        $description .= '...';
    }
    echo $description;
    ?>
        </p>
        <p><?php
	      echo $item['Item']['price'];
    ?></p>
    </div>
<?php
	    }

?>
</div>