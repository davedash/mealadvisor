<h2><?php echo link_to_restaurant($location->getRestaurant()) ?> location: <?php echo $location ?></h2>
<?php echo include_partial('location',  array( 'location' => $location));?>

<p><?php echo link_to('Is this information accurate?', '@feedback_location?restaurant='.$location->getRestaurant()->getStrippedTitle().'&location='.$location->getStrippedTitle(), 'class=lbOn') ?></p>