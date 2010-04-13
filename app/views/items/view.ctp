<?php
Configure::write('debug', 0);
?>
<h3><?php echo $item['Item']['title']; ?></h3>
<span class="dateCreated"><?php echo $item['Item']['created']; ?></span>
<?php if ($item['Item']['modified']) { ?>
<span class="dateModified"><?php echo $item['Item']['modified']; ?></span>
<?php } ?>
<p><?php echo $item['Item']['description']; ?></p>
<p><?php echo $item['Item']['price']; ?></p>
<input type="button" value="Buy" />