<?php
Configure::write('debug', 0);
$mouseOut = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'smallview',
                        $item['Item']['id']),
                        'update' => 'item-'.$item['Item']['id'],
                        // Doesn't show.
                        'loaded' => 'Effect.Opacity(\'item-'.$item['Item']['id'].'\')'));
?>
<h3><?php echo $item['Item']['title']; ?></h3>
<?php echo $item['Item']['created']; ?>
<p><?php
$description = substr($item['Item']['description'], 0, 150);
if (strlen($item['Item']['description']) > 150) {
    $description .= '...';
}
echo $description;
?></p>
<p><?php echo $item['Item']['price']; ?></p>