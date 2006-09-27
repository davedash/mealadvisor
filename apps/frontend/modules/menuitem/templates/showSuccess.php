<?php use_helper('Javascript','Object','Global') ?>

<h1><?php echo link_to_restaurant($restaurant); ?>: <strong><?php echo $menu_item->getName() ?></strong></h1>



<div class="food_foto">
	<?php echo tag('img', array('src'=>url_for('@menu_item_image?hashed_id=' . $menu_item->getHashedId()))) ?>
	<div class="caption"><?php echo link_to('Upload your own photo', '@menu_item_image_add?stripped_title=' . $menu_item->getStrippedTitle() . '&restaurant=' . $restaurant->getStrippedTitle()) ?></div>
</div>

<div class="information">

	<?php echo $menu_item->getHtmlDescription() ?>
	<?php if ($sf_user->isLoggedIn()): ?>
	  <p class="editlink"><?php echo link_to_menuitem_edit($menu_item, 'Edit description') ?></p>
	<?php endif ?>

	<?php if ($menu_item->getPrice()): ?>
	<label for="price">Price: </label> <?php echo $menu_item->getPrice() ?>
	<?php endif ?>
	<div id="<?php echo $menu_item->getUrl() .'_tags' ?>"> 
		<?php echo include_partial('menuitem/tags', array('menu_item' => $menu_item, 'add' => true));?>
	</div>
</div>

<?php if ($sf_user->isLoggedIn()): ?>
  
<?php echo form_remote_tag(array( 
	'url'    => '@tag_add', 
	'update' => $menu_item->getUrl() .'_tags',
	'complete' => "$('tag').value = ''; " . visual_effect('highlight', $menu_item->getUrl() .'_tags')
	))?>
	<div>
	Tag: 

		<?php echo input_hidden_tag('menuitem_hash', $menu_item->getHash()) ?> 
		<?php echo input_auto_complete_tag('tag', '', '@tag_autocomplete', 'autocomplete=on', array('use_style'=>true, 'tokens'=> ' '));?>
		<?php echo submit_tag('Tag') ?> 
	</div>  
</form>

<?php endif ?>
<div id="rating">
	<?php echo include_partial('rater', array('rating'=>$rating, 'menu_item' => $menu_item ));?>
</div>

<!--display reviews-->
<h2 style="clear:both">Comments about <?php echo $menu_item->getName()?></h2>
<div id="reviews">
<?php foreach ($menu_item->getMenuItemNotes() as $n): ?>
	<?php echo include_partial('restaurant/comment', array('comment' => $n, 'module'=>'menuitemnote' ));?>
<?php endforeach ?>
</div>

<!--review form-->
<!--review form-->
<?php if ($sf_user->isAuthenticated()): ?>
<div id="add_comment" class="comment_form">
	<?php echo form_remote_tag(array(
		'url' => '@menuitem_add_comment?restaurant=' . $restaurant->getStrippedTitle() . '&stripped_title=' . $menu_item->getStrippedTitle(),
		'script'	=> true,
		'update'   => array('success' => 'add_comment'),
		'loading'  => "Element.show('indicator')",
		'complete' => "Element.hide('indicator');".visual_effect('highlight', 'add_comment'),
		), 'class=form') ?>
		<fieldset>
			<label for="author"><?php echo 'author:' ?></label>
			<div>
				<?php echo $sf_user->getUsername() ?>

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

