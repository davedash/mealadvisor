<ul id="locations" class="locations">
	<?php foreach ($locations as $location): ?>
	<li>
		<?php echo include_partial('location/resultForRestaurants', array('location' => $location )) ?>
	</li>	  
	<?php endforeach ?>
</ul>
