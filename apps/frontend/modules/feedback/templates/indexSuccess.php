<?php echo form_tag('@feedback') ?>
	<label for="email">Your Email</label><?php echo input_tag('email') ?>
	<br/>
	<label for="message">Your Message
	</label>
	<?php echo textarea_tag('message') ?>
	<br/>
	<?php echo submit_tag('send') ?>
</form>