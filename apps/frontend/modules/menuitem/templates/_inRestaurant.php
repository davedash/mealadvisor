<p>
<?php echo $pager->getNbResults() ?> results found.<br />
Displaying results <?php echo $pager->getFirstIndice() ?> to  <?php echo $pager->getLastIndice() ?>.
</p>

<ul class="menuitems">
	<?php foreach ($pager->getResults() as $m): ?>
	<li class="menuitem">
		<div class="rater" id="<?php echo $m->getStrippedTitle() ?>_rating"><?php echo include_partial('menuitem/jointRater', array('menuitem' => $m ));?></div>
		<div class="item_info">
			<h3><?php echo link_to_menuitem($m); ?></h3>
			<?php echo $m->getHtmlDescription() ?>
			<?php echo include_partial('menuitem/tags', array('menu_item' => $m,'add' => false  ));?>
		</div>
	</li>
	<?php endforeach ?>
</ul>

<div id="pagination">
<?php if ($pager->haveToPaginate()): ?>
<!-- need to setup the following pages and have them work ajaxfully -->
<!-- setup routing rules, etc -->
<!-- clean this up a bit -->
  <?php echo link_to_remote('&laquo;', 
	array('url' => $nav_url . $pager->getFirstPage(), 
	'update' => 'menu_item_body') ) ?>
  <?php echo link_to_remote('&lt;', array('url' => $nav_url.$pager->getPreviousPage(), 'update' => 'menu_item_body' )) ?>
  <?php $links = $pager->getLinks(); foreach ($links as $page): ?>
    <?php echo ($page == $pager->getPage()) ? $page : link_to_remote($page, array('url' => $nav_url.$page, 'update' => 'menu_item_body' )) ?>
    <?php if ($page != $pager->getCurrentMaxLink()): ?> - <?php endif ?>
  <?php endforeach ?>
  <?php echo link_to_remote('&gt;', array('url' => $nav_url.$pager->getNextPage(), 'update' => 'menu_item_body' )) ?>
  <?php echo link_to_remote('&raquo;', array('url' => $nav_url.$pager->getLastPage(), 'update' => 'menu_item_body' )	) ?>
<?php endif ?>
</div>