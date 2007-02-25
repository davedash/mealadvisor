<?php echo form_tag('@openid_signin') ?>
	<p>
		<?php echo input_tag('openid_url') ?>
		<?php echo submit_tag('login') ?>
	</p>
</form>