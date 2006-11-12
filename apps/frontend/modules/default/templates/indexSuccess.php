<?php use_helper('MyText','Javascript');?>

<?php include_partial('default/header');?>
<div class="boxes">
	
	<div id = "restaurant_list" class="box">
		
		<?php include_component('location','freshest', array('near' => $sf_user->getLocation())) ?>
   		<p>Add a <?php echo link_to('new restaurant', '@restaurant_add') ?>.</p>
	</div>

	<div id ="features">
		<div id = "featured_item" class="box">
			<?php include_component('menuitem', 'feature') ?>
		</div>
		<div id = "latest_comments" class="box">
			<?php include_component('menuitemnote', 'latest') ?>
		</div>
	</div>
	
	
	<div id="cloud_box">
		<div class="box">
			<h2>Tags // Restaurants</h2>
			<?php include_component('tag', 'popularRestaurantCloud')?>
		</div>
		<div class="box" style = "clear:both">
		
			<h2 >Tags // Menu Items</h2>
			<?php include_component('tag', 'popularCloud')?>
		</div>
	</div>
</div>