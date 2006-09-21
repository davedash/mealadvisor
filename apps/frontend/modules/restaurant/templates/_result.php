<?php use_helper('Rating','Text');?>


<li>
<?php echo average_rating(array('rating' => !empty($user) ?  $restaurant->getUserRating($user) : $restaurant->getAverageRating(), 'votes' => $restaurant->getNumRatings(), 'omit_heading' => true, 'id' => $restaurant->getStrippedTitle() . '_average_rating', 'show_zero' => true )) ?>

	<?php echo link_to($restaurant->getName(), '@restaurant?stripped_title='.$restaurant->getStrippedTitle()) ?>
</li>
