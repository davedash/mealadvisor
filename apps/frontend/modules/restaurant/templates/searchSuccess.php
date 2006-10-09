 
<h2>restaurants matching "<?php echo htmlspecialchars($sf_params->get('search')) ?>"</h2>
<?php if (!empty($search_location)): ?>
  <p>Within <?php echo $radius ?> miles of <?php echo $search_location ?>.</p>
<?php endif ?>

<ul class="restaurants">
<?php foreach($restaurants as $restaurant): ?>
	
	<li>
		<?php echo include_partial('restaurant/resultWithDescription', array('restaurant' => $restaurant ));?>
	</li>
<?php endforeach ?>
</ul>
<?php if ($sf_params->get('page') > 1 && !count($restaurants)): ?>
  <div>There are no more results for your search.</div>
<?php elseif (!count($restaurants)): ?>
<p>No restaurants match your search.  Be the first to <?php echo link_to('add it', '@restaurant_add') ?>!

<?php endif ?>
 
<?php if (count($restaurants) == sfConfig::get('app_search_results_max')): ?>
  <div class="right">
    <?php echo link_to('more results &raquo;', '@search_restaurant?search='.$sf_params->get('search').'&page='.($sf_params->get('page', 1) + 1)) ?>
  </div>
<?php endif ?>


<h2>menu items matching "<?php echo htmlspecialchars($sf_params->get('search')) ?>"</h2>

<ul>
<?php foreach($items as $item): ?>
	<li>
		<h3><?php echo include_partial('menuitem/result', array('item' => $item));?></h3>
	</li>
<?php endforeach ?>
</ul>

<?php if ($sf_params->get('page') > 1 && !count($items)): ?>
  <div>There are no more results for your search.</div>
<?php elseif (!count($items)): ?>
  <div>Sorry, there are no menu items matching your search terms.</div>
<?php endif ?>

<?php if (count($items) == sfConfig::get('app_search_results_max')): ?>
  <div class="right">
    <?php echo link_to('more results &raquo;', '@search_restaurant?search='.$sf_params->get('search').'&page='.($sf_params->get('page', 1) + 1)) ?>
  </div>
<?php endif ?>
