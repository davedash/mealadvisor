<h2>Locations in <?php echo $in ?></h2>

<p>
<?php echo $pager->getNbResults() ?> results found.<br />
Displaying results <?php echo $pager->getFirstIndice() ?> to  <?php echo $pager->getLastIndice() ?>.
</p>


<?php foreach ($pager->getResults() as $location): ?>
  <?php include_partial('resultForRestaurants', array('location' => $location ));?>
<?php endforeach ?>

<div class="pagination">
<?php if ($pager->haveToPaginate()): ?>

	<?php if ($pager->getPage() > 1): ?>
	<?php echo link_to('&laquo;', $nav_url . 1) ?>
  <?php echo link_to('&lt;', $nav_url . $pager->getPreviousPage()) ?>  
	<?php endif ?>

  <?php foreach ($pager->getLinks() as $page): ?>
    <?php echo ($page == $pager->getPage()) ? $page : link_to($page, $nav_url . $pager->getFirstPage()) ?>
    <?php if ($page != $pager->getCurrentMaxLink()): ?> - <?php endif ?>
  <?php endforeach ?>

	<?php if ($pager->getPage() < $pager->getLastPage()): ?>
	<?php echo link_to('&gt;', $nav_url . $pager->getNextPage()) ?>
  <?php echo link_to('&raquo;', $nav_url . $pager->getLastPage()) ?>  
	<?php endif ?>

<?php endif ?>
</div>