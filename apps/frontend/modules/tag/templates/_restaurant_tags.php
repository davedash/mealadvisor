<?php use_helper('Restaurant');?>
<?php if (tags_for_restaurant($restaurant)): ?>

<?php echo image_tag('tag.png', 'alt=Popular Tags') ?> <?php echo tags_for_restaurant($restaurant) ?>
<?php endif ?>
