<?php use_helper('Javascript', 'MyText', 'Rating', 'Ymap');?>

<script type="text/javascript" 
src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=reviewsby.us"></script>


<?php if ($location instanceof Location): ?>
  
<script type="text/javascript" charset="utf-8">
	ymap.div = 'restaurant_map';

	//var myPoint = new YGeoPoint(<?php echo $location->getLatitude() ?>,<?php echo $location->getLongitude() ?>);
	Event.observe(window, "load", function() { 
		ymap.init(); 
		ymap.map.drawZoomAndCenter("<?php echo $location->getFullAddress() ?>", 4);
		var marker = new YMarker("<?php echo $location->getFullAddress() ?>");
		
		marker.addAutoExpand("<?php echo $location ?>");
		ymap.map.addOverlay(marker);
		ymap.map.disableKeyControls();
	});
	

</script>
<?php endif ?>


<div class="yui-gc">

	<div class="yui-u first">
		<div id="restaurant_header">
			<h2><?php echo $restaurant->getName() ?>
				<?php if ($restaurant->getChain()): ?><small>(chain)</small><?php endif ?>
			</h2>


			<div id="<?php echo $restaurant->getStrippedTitle() ?>_rating" class="rating">
				<?php echo include_partial('jointRater', array('restaurant' => $restaurant ));?>
			</div>
		</div>
		
		<!-- restaurant menu -->
		
		<ul id="restaurant_info">
			<?php if ($restaurant->getUrl()): ?>
			<li><?php echo link_to($restaurant->getName() . ' website',$restaurant->getUrl())?></li>
			<?php endif ?>
			<li>
				<?php echo link_to_function($num_locations . ' ' . pluralize($num_locations, 'location'),
				visual_effect('toggle_blind', 'location_body')) ?>
			</li>
		</ul>


		<div id="tag_area">
			<div id="<?php echo $restaurant->getStrippedTitle() .'_tags' ?>"> 
				<?php echo include_component('tag','restaurant', array('restaurant' => $restaurant)) ?>
			</div>
	
			<?php if ($sf_user->isLoggedIn()): ?>

			<?php echo form_remote_tag(array(
				'url'    => '@restaurant_tag_add', 
				'update' => $restaurant->getStrippedTitle() .'_tags',
				'complete' => "$('tag').value = ''; " . visual_effect('highlight',$restaurant->getStrippedTitle() .'_tags')
				), 'class=less_snug')?>

				<div>
					Tag: 

					<?php echo input_hidden_tag('restaurant', $restaurant->getStrippedTitle()) ?> 
					<?php echo input_auto_complete_tag('tag', '', 'tag/autocomplete', array('autocomplete'=>'off'), array('use_style'=>'true')) ?>					
					<?php echo submit_tag('Tag') ?> 
				</div>  
			</form>
			<?php endif ?>
		</div>

		<!-- information about the restaurant -->
		<div class="description">
			<?php if ($restaurant->getHtmlDescription()): ?>
				<?php echo $restaurant->getHtmlDescription() ?>
				<?php if ($sf_user->isLoggedIn()): ?>
					<?php echo link_to('edit description', '@restaurant_edit_description?stripped_title=' . $restaurant->getStrippedTitle()) ?>
				<?php endif ?>
			<?php else:?>
				<p>No description is available for this restaurant.  <?php echo link_to('Write one', '@restaurant_edit_description?stripped_title=' . $restaurant->getStrippedTitle()) ?>?</p>
			<?php endif ?>
		</div>
				
		<div id="locations" class="box">
			<div class="header">
				<h3>Restaurant locations</h3>
				<div class="hide">
					<?php echo link_to_function('show', toggle_element("location_body", 'location_hide'), 'id=location_hide')?>
				</div>	
			</div>
			<div class="body" id="location_body" style="display:none">
				<?php if (count($restaurant->getLocations())): ?>
				<ul class="locations">
					<?php foreach ($restaurant->getLocations() as $l): ?>
					<li><?php echo link_to_location($l, $l->getName()) ?>: <?php echo $l->toLargeString() ?></li>
					<?php endforeach ?>
				</ul>
				<p><?php echo link_to('Add a restaurant location', '@location_add?restaurant='.$restaurant->getStrippedTitle()) ?></p>
				<?php else: ?>
				<p>No locations... <?php echo link_to('add one', '@location_add?restaurant='.$restaurant->getStrippedTitle()) ?>!</p>
				<?php endif ?>
			</div>
		</div>		
			
		
	</div>
	<div class="yui-u">
		<div id="restaurant_map" style="width: 100%; height: 250px">
		</div>
		<p><?php echo link_to_location($location, $location->getName()) ?>: <?php echo $location->toLargeString() ?></p>
	</div>

	
</div>



<!-- begin... menu area-->

<div id="menu_item_box" class="box">
	<div class="header">
		<h3>Menu Items</h3>
<!-- 		we need to finish the ajax.updater call here... and populate the menu propperly -->
		<select id="options" onchange="new Ajax.Updater() ?>">
	        <option value="0">Hello</option>
	        <option value="1">World</option>
	      </select>
		<div class="hide">
			<?php echo link_to_function('hide', toggle_element('menu_item_body', 'menu_item_hide'), 'id=menu_item_hide') ?>
		</div>
	</div>
	<div class="body" id="menu_item_body" style="display:block">

		<!-- We need to call a component with the default settings "MenuItemPeer::ALL", page 1 -->
		<?php include_component('menuitem', 'inRestaurant', array('scope' => MenuItemPeer::ALL, 'page'=> 1, 'restaurant' => $restaurant  ))?>
	</div>
</div>

<!-- end menu -->

<div class="boxes">

<h2 style="clear: both">Menu items</h2>
<?php if (count($restaurant->getMenuItems())): ?>
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
<div><?php echo link_to('Login', '@sf_guard_signin') ?> and tell us what <em>you</em> think!</div>
<?php endif ?>

<!--/review form-->
</div>