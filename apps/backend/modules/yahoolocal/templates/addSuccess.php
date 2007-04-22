<h2>Adding a new restaurant/location</h2>
<p class="warning">
	See below to see if restaurants or locations match this place!
</p>

<?php echo form_tag('yahoolocal/addRestaurant') ?>
<fieldset>
	<ol>
		<li>
			<label for="name">Name:</label>
			<?php echo input_tag('name', $sf_request->getParameter('title')) ?>
		</li>
		<li>
			<label for="chain">Chain:</label>
			<?php echo checkbox_tag('chain') ?>
		</li>
		<li>
			<label for="description">Description</label>
			<?php echo textarea_tag('description', null,array('size'=>'30x3')) ?>
		</li>
		<li>
			<label for="url"><acronym title="Universal Resource Locator">URL</acronym></label>
			<?php echo input_tag('url',$sf_request->getParameter('url') ) ?>
		</li>
		<li>Address: <?php echo input_tag('address', $sf_request->getParameter('address')) ?></li>
		<li>City: <?php echo input_tag('city',$sf_request->getParameter('city')) ?></li>
		<li>State: <?php echo input_tag('state',$sf_request->getParameter('state')) ?></li>
		<li>Phone: <?php echo input_tag('phone', $sf_request->getParameter('phone')) ?></li>
		</li>
		
</fieldset>
<p>
	<?php echo submit_tag('ADD ONLY IF THIS IS NEW') ?>
</p>

<h2>The following restaurants match this restaurant name...</h2>
<?php if ($restaurants): ?>
	<ol>
		<?php foreach ($restaurants as $restaurant): ?>
		<li><?php echo $restaurant ?></li>
		<ul>
		<?php foreach ($restaurant->getLocations() as $location): ?>
		  <li><?php echo $location->toLargeString() ?></li>
		<?php endforeach ?>
		</ul>
		<?php endforeach ?>
	</ol>
<?php endif ?>

<h2>The following locations match this location</h2>
<p>Lat, Lng: <?php echo $sf_request->getParameter('latitude') ?>,
<?php echo $sf_request->getParameter('longitude') ?>


<?php if ($locations): ?>
	<dl>
		<?php foreach ($locations as $location): ?>
		<dt><?php echo $location->getRestaurant() ?></dt>
		<dd><?php echo $location->toLargeString() ?></dd>
		</dt>
		<?php endforeach ?>
	</dl>
<?php endif ?>