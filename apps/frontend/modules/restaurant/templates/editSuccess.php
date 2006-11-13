<?php use_helper('Object', 'Validation') ?>

<h1>New Restaurant</h1>

<h2>Restaurant Details</h2>

<?php echo form_tag('restaurant/update','name=add') ?>

<?php echo object_input_hidden_tag($restaurant, 'getId') ?>

<fieldset>
	<?php echo form_error('name') ?>
	<label for="name">Name:</label>
	<?php echo object_input_tag($restaurant, 'getName', array('size' => 20 )) ?>
	<br />

	<label for="chain">Chain:</label>
	<?php echo object_checkbox_tag($restaurant, 'getChain') ?>
	<br />
	<label for="description">Description</label>
	<?php echo object_textarea_tag($restaurant, 'getDescription', array('size'=>'30x3')) ?>
	<?php echo include_partial('default/markdown') ?>

	<label for="url"><acronym title="Universal Resource Locator">URL</acronym></label>
	<?php echo object_input_tag($restaurant, 'getUrl') ?>

</fieldset>
<?php if (!$restaurant->getId()): ?>
<h2>Location Details</h2>
<p class="instructions">
	Enter any location specific information for this restaurant.  If there are multiple locations you can add them later.  All fields are necessary in order for a location to be submitted.
</p>

  
<fieldset>
	<label for="location_name">Location&nbsp;Name:</label> 
	<?php echo input_tag('location_name', '', array (
	  'size' => 20,
	)) ?> (If something other than <em>City, State</em> e.g. <em>Southdale Mall</em> or leave blank)
	<br />
<label for="address">Address:</label>
<?php echo input_tag('address', '', array ('size' => 20)) ?>
<br />

<label for="city">City</label>
<?php echo input_tag('city','',  array ('size' => 20)) ?>
<br />

<label for="state">State</label>
<?php echo input_tag('state','',  array ('size' => 20)) ?>
<br />
<label for="zip">Zip</label>
<?php echo input_tag('zip', '', array ('size' => 7)) ?>
<br />

<label for="phone">Phone</label>
<?php echo input_tag('phone','',  array ('size' => 12)) ?>
</fieldset>
<h2>Review</h2>
<p class="instructions">
	Add your initial review of the restaurant.  Tell us how great the place is, or if we should consider dining elsewhere.
</p>
<fieldset>
	<label for="review">Comment:</label>
<?php echo textarea_tag('review', '', 'size=40x10') ?>
</fieldset>
<?php endif ?>

<div>
	<?php echo submit_tag('add') ?>
	&nbsp;<?php echo link_to('cancel', '@homepage') ?>
</div>

</form>
