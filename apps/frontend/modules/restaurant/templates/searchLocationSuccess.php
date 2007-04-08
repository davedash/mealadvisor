<h2>restaurants matching "<?php echo htmlspecialchars($sf_params->get('search')) ?>"
	<br/>	
	<?php if ($in): ?>
	in <?php echo $search_location ?>
	<?php else:?>
	near <?php echo $search_location ?>
		
	<?php endif ?>
</h2>


<ul class="restaurants">
<?php foreach($locations as $location): ?>
	
	<li>
		<?php echo include_partial('restaurant/resultWithDescription', array('restaurant' => $location->getRestaurant() ));?>
		<br />
		<?php echo $location->search_distance ?> miles
	</li>
<?php endforeach ?>
</ul>
<?php if ($sf_params->get('page') > 1 && !count($locations)): ?>
  <div>There are no more results for your search.</div>
<?php elseif (!count($locations)): ?>
<p>No restaurants match your search try 
	
<?php echo link_to('searching for ' .htmlspecialchars($sf_params->get('search')). trim($search_location) ? " outside of $search_location" : ' Anywhere','@search_restaurant?search=' . $sf_params->get('search')) ?>.

<?php endif ?>
 
<?php if (count($locations) == sfConfig::get('app_search_results_max')): ?>
  <div class="right">
    <?php echo link_to('more results &raquo;', '@search_restaurant?search='._or($sf_params->get('search'),'Anything').'&location='.$sf_params->get('location','Anywhere').'&page='.($sf_params->get('page', 1) + 1)) ?>
  </div>
<?php endif ?>

