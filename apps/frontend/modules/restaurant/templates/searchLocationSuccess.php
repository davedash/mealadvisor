
<?php if ($sf_params->get('search') && trim($search_location)): ?>
<h2>restaurants matching "<?php echo htmlspecialchars($sf_params->get('search')) ?>"</h2>
<p>Within <?php echo $radius ?> miles of <?php echo $search_location ?>.</p>

<?php elseif(!$sf_params->get('search')):?>
<h2>Restaurants near <?php echo $search_location ?></h2>

<?php else:?>
<p>Your location didn't make sense, try entering a zip-code or a city.</p>  

<?php endif ?> 


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
<?php echo link_to('searching for ' .htmlspecialchars($sf_params->get('search')). trim($search_location) ? " outside of $search_location" : ' Anywhere','@search_restaurant?location=Anywhere&search=' . $sf_params->get('search')) ?>.

<?php endif ?>
 
<?php if (count($locations) == sfConfig::get('app_search_results_max')): ?>
  <div class="right">
    <?php echo link_to('more results &raquo;', '@search_restaurant?search='._or($sf_params->get('search'),'Anything').'&location='.$sf_params->get('location','Anywhere').'&page='.($sf_params->get('page', 1) + 1)) ?>
  </div>
<?php endif ?>

