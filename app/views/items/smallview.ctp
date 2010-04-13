<?php
Configure::write('debug', 0);

    $mouseOver = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'preview',
                        $item['Item']['id']),
                        'update' => 'item-'.$item['Item']['id'],
                        // Doesn't show.
                        'loaded' => 'Effect.Opacity(\'item-'.$item['Item']['id'].'\')'));
    $click = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'view',
                        $item['Item']['id']),
                        'update' => 'item-'.$item['Item']['id'],
                        'loaded' => 'Effect.BlindDown(\'item-'.$item['Item']['id'].'\')'));
?>

        <h4 onmouseover="<?php
                echo $mouseOver;
                ?>" onclick="<?php echo $click; ?>">
                <?php echo $item['Item']['title']; ?></h4>
        <p onmouseover="<?php
                echo $mouseOver;
                ?>" onclick="<?php echo $click; ?>">
<?php
$description = substr($item['Item']['description'], 0, 5);
if (strlen($item['Item']['description']) > 5) {
    $description .= '...';
}
echo $description;
?></p>
        <p onmouseover="<?php
                echo $mouseOver;
                ?>" onclick="<?php echo $click; ?>">
                <?php echo $item['Item']['price']; ?></p>