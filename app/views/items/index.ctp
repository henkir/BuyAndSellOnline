
<?php

foreach ($items as $item) {
    $id = $item['Item']['id'];
    $mouseOver = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'preview',
                        $id),
                        'update' => 'item'.$id));

    $click = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'view',
                        $id),
                        'update' => 'item'.$id,
                        'loaded' => 'Effect.BlindDown(\'item'.$id.'\')'));

    $mouseOut = $ajax->remoteFunction(
                    array('url' => array('controller' => 'items',
                        'action' => 'smallview',
                        $id),
                        'update' => 'item'.$id));
    ?>
    <div id="item<?php
            echo $id;
            ?>" class="item" onmouseover="<?php
            echo $mouseOver;
            ?>" onclick="<?php
            echo $click;
            ?>" onmouseout="<?php
            echo $mouseOut;
            ?>">
        <h4><?php
            echo $item['Item']['name'];
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