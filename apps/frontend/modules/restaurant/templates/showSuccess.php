<?php use_helper('Global','Javascript', 'MyText');?>
<h1><?php echo $restaurant->getName() ?>
	<?php if ($restaurant->getChain()): ?>
		<em>(chain)</em>
	<?php endif ?>
</h1>

<div id="subnav">
		<?php if ($restaurant->getUrl()): ?>
		<?php echo link_to($restaurant->getName() . ' website',$restaurant->getUrl())?>
		<?php endif ?>
		
		<?php echo link_to_function($num_locations . ' ' . pluralize($num_locations, 'location'),
		visual_effect('toggle_blind', 'locations')) ?>
</div>

<div class="description">
	<?php if ($restaurant->getHtmlDescription()): ?>
	<?php echo $restaurant->getHtmlDescription() ?>
	<?php if ($sf_user->isLoggedIn()): ?>
	<?php echo link_to('change description', '@restaurant_edit_description?stripped_title=' . $restaurant->getStrippedTitle()) ?>
	  
	<?php endif ?>
	<?php else:?>
	<p>No description is available for this restaurant.  <?php echo link_to('Write one', '@restaurant_edit_description?stripped_title=' . $restaurant->getStrippedTitle()) ?>?</p>
	<?php endif ?>
	<div id="<?php echo $restaurant->getStrippedTitle() .'_tags' ?>"> 
		<?php echo include_partial('restaurant/tags', array('restaurant' => $restaurant, 'add' => true));?>
	</div>
</div>

<?php if ($sf_user->isLoggedIn()): ?>
  
<?php echo form_remote_tag(array( 
	'url'    => '@restaurant_tag_add', 
	'update' => $restaurant->getStrippedTitle() .'_tags',
	'complete' => "$('tag').value = ''; " . visual_effect('highlight',$restaurant->getStrippedTitle() .'_tags')
	))?>
	<div>
	Tag: 

		<?php echo input_hidden_tag('restaurant', $restaurant->getStrippedTitle()) ?> 
		<?php echo input_auto_complete_tag('tag', '', '@tag_autocomplete', 'autocomplete=on', array('use_style'=>true, 'tokens'=> ' '));?>
		<?php echo submit_tag('Tag') ?> 
	</div>  
</form>

<?php endif ?>

<div id="rating">
	<?php echo include_partial('rater', array('rating'=>$rating, 'restaurant' => $restaurant ));?>
</div>


<div class="boxes">
<div id="locations" style="display: none">
	<h2>Restaurant locations <em> <?php echo link_to_function('hide',
	visual_effect('blind_up', 'locations'))?></em></h2>
	<?php if (count($restaurant->getLocations())): ?>
	<ul class="locations">
		<?php foreach ($restaurant->getLocations() as $l): ?>
		<li><?php echo link_to_location($l) ?> - <?php echo $l->toLargeString() ?></li>
		<?php endforeach ?>
	</ul>
	<p><?php echo link_to('Add a restaurant location', '@location_add?restaurant='.$restaurant->getStrippedTitle()) ?></p>
	<?php else: ?>
	<p>No locations... <?php echo link_to('add one', '@location_add?restaurant='.$restaurant->getStrippedTitle()) ?>!</p>
	<?php endif ?>
</div>
<h2 style="clear: both">Menu items</h2>
<?php 
	if (count($restaurant->getMenuItems())): 
		$ad_placed = false;
?>
	<ul class="menuitems">
		<?php foreach ($restaurant->getMenuItems() as $m): ?>
		<li class="menuitem">
			<div class="rater" id="<?php echo $m->getStrippedTitle() ?>_rating"><?php echo include_partial('menuitem/jointRater', array('menuitem' => $m ));?></div>
			<div class="item_info">
				<h3><?php echo link_to_menuitem($m); ?></h3>
			
				<?php echo $m->getHtmlDescription() ?>
				<?php echo include_partial('menuitem/tags', array('menu_item' => $m,'add' => false  ));?>
			</div>
		</li>
		<?php if (!$ad_placed && !mt_rand(0,2)): 
			$ad_placed = true;
		?>
		<li class="menuitem">
			<?php echo include_partial('internal_ad');?>
		
		</li>		  
		<?php endif ?>
		<?php endforeach ?>
		<?php if (!$ad_placed): ?>
		<li class="menuitem">
		<?php echo include_partial('internal_ad');?>
		</li>
		<?php endif ?>
	</ul>
	<p style="clear: both"><?php echo link_to('Add a menu item', '@menu_item_add?restaurant='.$restaurant->getStrippedTitle()) ?></p>
<?php else: ?>
  <p>No items on the menu yet... <?php echo link_to('add one', '@menu_item_add?restaurant='.$restaurant->getStrippedTitle()) ?>!</p>
<?php endif ?>

<h2>Comments about <?php echo $restaurant->getName() ?></h2>
<div id="reviews">
	<?php foreach ($restaurant->getReviews() as $n): ?>
	<?php echo include_partial('comment', array('comment' => $n ));?>
	<?php endforeach ?>
</div>

<!--review form-->
<?php if ($sf_user->isAuthenticated()): ?>

<div id="add_comment" class="comment_form">
	<?php echo form_remote_tag(array(
		'url' => '@restaurant_add_comment?stripped_title='.$restaurant->getStrippedTitle(),
		'update'   => array('success' => 'add_comment'),
		'script' => true,
		'loading'  => "Element.show('indicator')",
		'complete' => "Element.hide('indicator');".visual_effect('highlight', 'add_comment'),
		), 'class=form') ?>
		<fieldset>
			<label for="author"><?php echo 'author:' ?></label>
			<div>
				<?php echo link_to_user($sf_user) ?>
			</div>
			<label for="body">comment:</label>
			<?php echo textarea_tag('body', $sf_params->get('body'), 'size=40x10') ?>
			<?php echo include_partial('default/markdown') ?>
		</fieldset>
		<div class="in_form">
			<?php echo submit_tag('tell') ?>
		</div>
	</form>		
</div>
<?php else: ?>
<div><?php echo link_to('Login', '@login') ?> and tell us what <em>you</em> think!</div>
<?php endif ?>

<!--/review form-->
</div>