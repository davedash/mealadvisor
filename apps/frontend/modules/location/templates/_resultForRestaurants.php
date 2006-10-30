<?php use_helper('Rating');?>
<?php echo average_rating(array('rating' => $location->getRestaurant()->getAverageRating(), 'votes' => $location->getRestaurant()->getNumRatings(), 'omit_heading' => true, 'id' => $location->getRestaurant()->getStrippedTitle() . '_average_rating', 'show_zero' => true )) ?>

<div class="restaurant"><?php echo link_to_restaurant($location->getRestaurant()) ?></div>
<div class="address"><?php echo $location->getFullAddress('%a, %c, %s, %z') ?></div>