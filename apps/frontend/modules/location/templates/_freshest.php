<h2>Restaurants // Freshest</h2>

<p class="location_header description">near <?php echo $sf_user->getLocation() ?></p>  

<ul id="locations" class="locations">
	<?php foreach ($locations as $location): ?>
	<li>
		<?php echo include_partial('location/resultForRestaurants', array('location' => $location )) ?>
	</li>	  
	<?php endforeach ?>
</ul>
