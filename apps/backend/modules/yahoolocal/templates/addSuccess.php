<?php use_helper('Javascript');?>

<p><?php echo link_to('< results', 'yahoolocal/index') ?></p>

<h1>Adding a new restaurant and/or location</h1>
<p><?php echo $result->Title ?> @ <?php echo $result->Address ?>, <?php echo $result->City ?>, <?php echo $result->State ?></p>

<?php if ($locations): ?>
<h2>The following locations match this location</h2>

<p><a href="#add_restaurant_form">It's none of these.</a></p>
<p>
	The following restaurants near (<?php echo $result->Latitude ?>, 
	<?php echo $result->Longitude ?>) came up.
</p>

	<dl>
		<?php foreach ($locations as $location): ?>
		<dt><?php echo $location->getRestaurant() ?></dt>
		<dd><?php echo $location->toLargeString() ?></dd>
		</dt>
		<?php endforeach ?>
	</dl>
<?php endif ?>


<?php if ($restaurants): ?>
<h2>The following restaurants have a similar name...</h2>
<p><a href="#add_restaurant_form">It's none of these.</a></p>
	<ol>
		<?php foreach ($restaurants as $restaurant): ?>
		<li><?php echo link_to($restaurant->getName(),'restaurant/edit?id='.$restaurant->getId()) ?>

			<?php echo link_to_remote('add '.$result->Address,  
			array('url' => 'yahoolocal/addLocation?restaurant='.$restaurant->getId().'&yid='.$result['id'],
			 'update' => 'locations_'.$restaurant->getId())) ?>
			
		</li>
		<div id="locations_<?php echo $restaurant->getId() ?>">
			<?php include_partial('yahoolocal/locations', array('restaurant' => $restaurant, 'yid' =>$result['id'],  ));?>
		</div>
		<?php endforeach ?>
	</ol>
<?php endif ?>

<p class="warning">
	See below to see if restaurants or locations match this place!
</p>

<?php echo form_tag('yahoolocal/addRestaurant','id=add_restaurant_form') ?>
<fieldset>
	<ol>
		<li>
			<label for="name">Name:</label>
			<?php echo input_tag('name', $result->Title) ?>
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
			<?php echo input_tag('url',$result->BusinessUrl) ?>
		</li>
		<li>Address: <?php echo input_tag('address', $result->Address) ?></li>
		<li>City: <?php echo input_tag('city',$result->City) ?></li>
		<li>State: <?php echo input_tag('state',$result->State) ?></li>
		<li>Phone: <?php echo input_tag('phone', $result->Phone) ?></li>
		</li>
		
</fieldset>
<p>
	<?php echo input_hidden_tag('yid',$result['id']) ?>
	<?php echo submit_tag('add') ?>
</p>
</form>


