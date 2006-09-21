
<?php use_helper('Global');?>
<h1>latest restaurants</h1>
<?php echo $pager->getNbResults() ?> results found.<br /> 
Displaying results <?php echo $pager->getFirstIndice() ?> to  <?php echo $pager->getLastIndice() ?>

<ul class="restaurants">
<?php foreach ($pager->getResults() as $restaurant): ?>
<li><?php include_partial('restaurant/resultWithDescription', array('restaurant' => $restaurant)) ?></li>
<?php endforeach ?>
</ul>

<?php if ($pager->haveToPaginate()): ?>
  <?php echo link_to('&laquo;', '@latest_restaurants_page?page='.$pager->getFirstPage()) ?>
  <?php echo link_to('&lt;', '@latest_restaurants_page?page='.$pager->getPreviousPage()) ?>
  <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
    <?php echo ($page == $pager->getPage()) ? $page : link_to($page, '@latest_restaurants_page?page='.$page) ?>
    <?php if ($page != $pager->getCurrentMaxLink()): ?> - <?php endif ?>
  <?php endforeach ?>
  <?php echo link_to('&gt;', '@latest_restaurants_page?page='.$pager->getNextPage()) ?>
  <?php echo link_to('&raquo;', '@latest_restaurants_page?page='.$pager->getLastPage()) ?>
<?php endif ?>