<h2>Upload a picture of <?php echo $menu_item ?> from <?php echo $restaurant ?></h2>
<p class="information">Images need to be 240<abbr title="pixels">px</abbr> wide x 180<abbr title="pixels">px</abbr> high.  Or smaller... but not much smaller.</p>
<?php echo form_tag('@menu_item_image_add?restaurant=' . $restaurant->getStrippedTitle() . '&stripped_title=' . $menu_item->getStrippedTitle(), 'multipart=true') ?>
	<label for="image_file">Image File</label><?php echo input_file_tag('image_file') ?>
	<br />
	<!--span class="big_or">or</span>
	<br />
	<label for="url">Image from <acronym title="Universal Resource Locator">URL</acronym></label> 
	<?php echo input_tag('url') ?>
	<br/-->
	<?php echo submit_tag('submit') ?>
</form>