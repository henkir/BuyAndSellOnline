<h2><?php echo $tag['Tag']['name']; ?></h2>
<table>
<?php foreach($tag['Item'] as $item) { ?>
<tr><td><?php echo $ajax->link($item['name'], array('controller' => 'items', 'action' => 'smallview', $item['id']), array('update' => 'content')); ?></td></tr>
<?php } ?>
</table>