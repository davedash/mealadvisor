<?php use_helper('Restaurant');?>
<?php echo include_partial('tag/restaurant_tags', array('restaurant' => $restaurant)) ?>
<?php if ($sf_user->isLoggedIn() && !empty($add)): ?>
	<?php if (tags_for_restaurant_from_user($restaurant, $sf_user->getUser())): ?>
		<br/>Your Tags: <?php echo tags_for_restaurant_from_user($restaurant, $sf_user->getUser()) ?>
	<?php endif ?>
<?php endif ?>
