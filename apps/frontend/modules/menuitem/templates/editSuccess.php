<?php use_helper('Object') ?>
<h1><?php echo $restaurant->getName() ?></h1>

<?php if ($menu_item->getId()): ?>
<h2><?php echo $menu_item->getName() ?></h2>

<?php else: ?>
<h2>New Menu Item</h2>
  
<?php endif ?>

<p class="information">
	The descriptions are <strong>meant to be objective</strong>.  Leave commentary for the comments about this menu item.
</p>

<?php if ($menu_item->getId()): ?>
<?php echo form_tag('@menu_item_edit?restaurant='.$restaurant->getStrippedTitle() . '&stripped_title=' . $menu_item->getStrippedTitle() ,'id=add_item') ?>

<?php else: ?>
<?php echo form_tag('@menu_item_add?restaurant='.$restaurant->getStrippedTitle() ,'id=add_item') ?>
<?php endif?>

<?php echo object_input_hidden_tag($menu_item, 'getId') ?>
<fieldset>
	<label for="name">Name</label>
	<?php echo object_input_tag($menu_item, 'getName', array (
	  'size' => 20, 'disabled' => $menu_item->getId() ? true : false 
	)) ?><br/>
	
	<label for="description">Description</label>
	<?php echo object_textarea_tag($menu_item, 'getDescription', array (
	  'size' => '60x7',
	)) ?>
	<br />
	<?php echo include_partial('default/markdown');?>
	<br />
	<label for="price">Price</label>
	<?php echo object_input_tag($menu_item, 'getPrice', array('size' => '20')); ?>
	<br />	
</fieldset>
<?php echo submit_tag('save') ?>
</form>
