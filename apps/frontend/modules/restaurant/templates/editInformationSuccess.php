<?php use_helper('Global','Object');?>
<h2>Description for <?php echo $restaurant ?></h2>

<?php echo form_tag('@restaurant_edit_description?stripped_title=' . $restaurant->getStrippedTitle()) ?>
	<label for="chain">Chain</label>
	<?php echo object_checkbox_tag($restaurant, 'getChain') ?>
	<br/><label for="description">
		Current Description <?php echo markdown_enabled_link() ?>:
	</label>
	<?php echo object_textarea_tag($restaurant, 'getDescription', 'cols=80 rows=10') ?>
	<br/>
	<label for="url">URL:</label>
	<?php echo object_input_tag($restaurant, 'getUrl') ?><br/>
	<?php echo submit_tag('save') ?>

</form>