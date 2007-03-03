<h2>The page for <?php echo $restaurantRedirect->getRestaurant() ?> has moved...</h2>

<p>
	The restaurant that used to be located at 
	<?php echo url_for('@restaurant?stripped_title='.$restaurantRedirect->getOldStrippedTitle()) ?>
	has moved <?php echo link_to('here', '@restaurant?stripped_title='.$restaurantRedirect->getRestaurant()->getStrippedTitle()) ?>.
</p>