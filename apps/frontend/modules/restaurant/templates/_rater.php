<?php use_helper('Rating');?>

<div class="rating">
<?php echo rater(array('user' => $sf_user, 'rating' => $rating, 'object'=>$restaurant, 'update' => 'rating', 'message' => 'Eat here again?' )) ?>
</div>
<div class="rating">

<?php echo average_rating(array('rating' => $restaurant->getAverageRating(), 'votes' => $restaurant->getNumRatings())) ?>
</div>