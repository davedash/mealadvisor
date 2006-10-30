<h2>Reviews // Latest</h2>
<ul class="bulletless">
<?php foreach ($notes as $note): ?>
  <li>
	<div class = "comment">
		<?php echo $note->getExcerpt() ?>
	</div>
	<div class="meta">

		<span class="item">
			<?php echo link_to_menuitem($note->getMenuItem()) ?>
		</span>
		@
		<span class="restaurant">
			<?php echo link_to_restaurant($note->getMenuItem()->getRestaurant()) ?>
		</span>				
	</div>
  </li>
<?php endforeach ?>
</ul>
