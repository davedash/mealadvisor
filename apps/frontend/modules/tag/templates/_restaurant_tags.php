<?php use_helper('Restaurant');?>
<?php if (tags_for_restaurant($restaurant)): ?>

<p>Tags: <?php echo tags_for_restaurant($restaurant) ?></p>
<?php endif ?>
