<?php use_helper('Rating');?>
<dl>
  <?php foreach ($images as $image): ?>
    <dt><?php echo image_for_item($image->getMenuitem(), 'longest_side=125') ?></dt>
    <dl>
      <h3><?php echo link_to_menuitem($image->getMenuitem()) ?></h3>
      <p class="description">
        <?php echo link_to_restaurant($image->getMenuitem()->getRestaurant()) ?>
      </p>      
    </dl>
  <?php endforeach ?>
</dl>