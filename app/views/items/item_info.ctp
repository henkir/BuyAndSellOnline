<?php
// We don't want any debug output in the AJAX.
Configure::write('debug', 0);
// If $items is set, the user has not chosen any item yet.
if (isset($items)) {
    foreach ($items as $item) { ?>
        <div>
        <?php
            echo $ajax->link($item['Item']['title'],
                    '/items/view/'.$item['Item']['id'],
                    array('update' => 'itemInfo',
                          'complete' => 'Effect.BlindDown(\'itemInfo\')'));
        ?>
        </div>
    <?php
    }
}
// The item the user clicks on will be shown here.
echo $ajax->div('itemInfo'); ?>
<h3><?php echo $item['Item']['title']; ?></h3>
<?php echo $item['Item']['created']; ?>
<p><?php echo $item['Item']['description']; ?></p>
<?php echo $ajax->divEnd('itemInfo'); ?>