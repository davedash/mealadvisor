<?php echo use_helper('Javascript');?>
<h1><?php echo $user ?></h1>

<h2>Restaurants <small>reviewed or rated by <?php echo $user ?></small></h2>
<ul id="user_restaurants" class="profile">
<?php foreach ($restaurants as $restaurant): ?>
  <?php echo include_partial('restaurant/result',array('restaurant' => $restaurant, 'user'=>$user));?>
<?php endforeach ?>
<?php if (count($moreRestaurants) > 0): ?>
<span id="user_restaurants_more" style="display: none">
	<?php foreach ($moreRestaurants as $restaurant): ?>
	<?php echo include_partial('restaurant/result',array('restaurant' => $restaurant, 'user'=>$user));?>
	<?php endforeach ?>

</span>
<p><?php echo link_to_function('<span id="user_restaurants_more_text">show all ' . (count($moreRestaurants)+10) . '</span> restaurants', "toggle_extra('user_restaurants_more')") ?></p>
  
<?php endif ?>
</ul>

<?php echo javascript_tag('function toggle_extra(div) 
{
	if (Element.visible(div)) {
		new Effect.BlindUp(div);
		$(div+"_text").innerHTML = "show additional ";
	} else {
		new Effect.BlindDown(div);
		$(div+"_text").innerHTML = "hide additional";
	}
}

') ?>