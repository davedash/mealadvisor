<?php use_helper('Rating','Text');?>

<?php echo average_rating(array('rating' => $restaurant->getAverageRating(), 'votes' => $restaurant->getNumRatings(), 'omit_heading' => true, 'id' => $restaurant->getStrippedTitle() . '_average_rating', 'show_zero' => true )) ?>

<h3>
	<?php echo link_to($restaurant->getName(), '@restaurant?stripped_title='.$restaurant->getStrippedTitle()) ?>
	<em> - updated: <?php echo $restaurant->getUpdatedAt() ?>
		
	</em>
</h3>

<p class="short_desc"><?php echo truncate_text(strip_tags($restaurant->getHtmlDescription()),100) ?></p>
