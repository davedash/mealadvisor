<?php use_helper('MyText','Ymap','Javascript');?>

<script type="text/javascript" 
src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=reviewsby.us"></script>
<style type="text/css">
#restaurantMap {
  height: 300px; 
  width: 50%;
float: right; 
} 
</style>
<?php echo include_partial('default/header');?>
<div class="boxes">
<div id="restaurant_list">
	<h2>Latest Restaurants (<?php echo $num_restaurants . ' ' . pluralize($num_restaurants, 'restaurant')?> total)
		<!--em><?php //echo link_to_function('map','ymap.init();'.visual_effect('appear','restaurantMap')) ?></em-->
	</h2>
	<div id="restaurantMap"></div>
	<?php  echo ymap_overlay_rss('@feed_latest_georss') ?>
	<ul class="restaurants">
		
		<?php foreach ($restaurants as $restaurant): ?>
		<li>
			<?php echo include_partial('restaurant/resultWithDescription', array('restaurant' => $restaurant ));?>
		</li>
	<?php endforeach ?>
	</ul>
	<h3><?php echo link_to('more...', '@latest_restaurants') ?></h3>
	<p>Add a <?php echo link_to('new restaurant', '@restaurant_add') ?>.</p>
</div>

<div id="cloud_box">
	<h2>Browse restaurants by popular tags</h2>
	<?php echo include_component('tag', 'popularRestaurantCloud')?>
	
	<h2 style="clear:both">Browse menu items by popular tags</h2>
	<?php echo include_component('tag', 'popularCloud')?>
</div>
</div>