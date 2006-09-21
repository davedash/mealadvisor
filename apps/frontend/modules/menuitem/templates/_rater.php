<?php use_helper('Rating');?>

<div class="rating">
<?php echo rater(array('user' => $sf_user, 'rating' => $rating, 'object'=>$menu_item, 'update' => 'rating', 'message' => 'Eat this again?', 'module' => 'menuitem' )) ?>
</div>
<div class="rating">

<?php echo average_rating(array('rating' => $menu_item->getAverageRating(), 'votes' => $menu_item->getNumRatings())) ?>
</div>