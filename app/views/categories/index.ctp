<h1>Categories</h1>
<?php foreach ($categories as $category) { ?>
<div>
<?php echo $category['Category']['name']; ?>
<?php echo $html->link('Delete', array('action' => 'delete', 'id' => $category['Category']['id']), null, 'Delete '.$category['Category']['name'].'?'); ?>
</div>
<?php } ?>