<div id="recent_dishes">
<h2>Recent Dishes</h2>
<?php include_component('menuitem', 'feature') ?>
</div>

<p>View <?php echo link_to('all restaurants', '@restaurant_list') ?> 
<?php echo rss_link_to('feed/freshest') ?>.</p>




<div class="boxes">
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