<?php use_helper('Rating');?>
<ul>
  
  <?php 
  $count = count($images); 
  for($i = 0; $i < $count; $i++): 
  $image = $images[$i];
  ?>
  <?php if ($i+1 <> $count): ?>
  <li>
  <?php else:?>
  <li class="last">
  <?php endif ?>

    <div class="image">
      <?php echo image_for_item($image->getMenuitem(), 'longest_side=125') ?>
    </div>
    
    <h3>
      <?php echo link_to_menuitem($image->getMenuitem(),'truncate=16') ?>
      
      from 
      
      <?php echo link_to_restaurant($image->getMenuitem()->getRestaurant()) ?>
    </h3>      

  </li>
  <?php endfor ?>
</ul>
