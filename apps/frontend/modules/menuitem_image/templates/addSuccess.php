<h2>Upload a picture of <?php echo $menu_item->getName() ?> from <?php echo $restaurant ?></h2>

<ul class="information">
	<li>Images need to be 240<abbr title="pixels">px</abbr> wide x 180<abbr title="pixels">px</abbr> high.  Or smaller... but not much smaller.</li>
	<li>They also need to be smaller than 100 <acronym title="Kilobytes">Kb</acronym>.</li>
</ul>

<?php echo form_tag('@menu_item_image_add?restaurant=' . $restaurant->getStrippedTitle() . '&stripped_title=' . $menu_item->getStrippedTitle(), 'multipart=true') ?>
<?php echo form_error('image_file') ?>
	<label for="image_file">Image File</label><?php echo input_file_tag('image_file') ?>

	<?php echo submit_tag('submit') ?>
</form>