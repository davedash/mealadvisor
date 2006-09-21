<?php use_helper('Global');?>

<h2>Is there a problem with our information on <?php echo link_to_restaurant($restaurant) ?> in <?php echo link_to_location($location) ?>?</h2>

<p>This is what we know:</p>
<blockquote>
	<?php echo include_partial('location/location', array('location'=>$location));?>
</blockquote>

<p>If you can give us <em>updated</em> information about this location please let us know!</p>

<?php echo include_partial('contact_form', array('info' => "There is a problem with the information for " . link_to_restaurant($restaurant) . ' at ' . link_to_location($location).'.', 'subject' => "{$restaurant->getName()} in {$location->__toString()}"));?>