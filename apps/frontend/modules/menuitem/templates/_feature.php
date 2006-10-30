<div id="feature_foto">
	<?php echo image_for_item($item, 'longest_side=125') ?>
</div>
<h2><?php echo link_to_menuitem($item) ?></h2>

<p class="description"><?php echo link_to_restaurant($item->getRestaurant()) ?></p>
<div class="rating">
	<?php echo average_rating(array('rating' => $item->getAverageRating(), 'votes' => $item->getNumRatings(), 'omit_heading'=>true)) ?>
</div>
<div class="description">
	<?php echo $item->getDescription() ?>
</div>