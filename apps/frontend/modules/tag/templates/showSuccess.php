<?php use_helper('Ymap');?>
<script type="text/javascript" 
src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=reviewsby.us"></script>
<style type="text/css">
#restaurantMap {
  height: 300px; 
  width: 50%;
  float: right; 
} 
</style>

<h2>Restaurants tagged <em><?php echo $tag ?></em></h2>

<ul>
<?php foreach ($restaurants as $r): ?>
<li>	<?php echo link_to_restaurant($r) ?></li>
<?php endforeach ?></ul>

<h2>Items tagged <em><?php echo $tag ?></em></h2>
<div id="restaurantMap"></div>
<?php echo ymap_overlay_rss('@feed_tag_georss?tag='.$tag) ?>

<ul>
<?php foreach ($items as $i): ?>
<li>	<?php echo link_to_menuitem($i) ?> - <?php echo link_to_restaurant($i->getRestaurant()) ?></li>
<?php endforeach ?></ul>