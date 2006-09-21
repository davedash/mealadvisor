<h2>login</h2>
<div id="login">
	<h2>sign in</h2>
	
	<?php echo form_tag('user/localLogin') ?>
    <?php echo input_hidden_tag('referer', $sf_params->get('referer') ? $sf_params->get('referer') : $sf_request->getUri()) ?>
	<?php echo form_error('username') ?>
	<?php echo form_error('password') ?>
	
	<label for="username">username</label><?php echo input_tag('username') ?>
	
	<label for="password">password</label><?php echo input_password_tag('password') ?>
    <?php echo submit_tag('login') ?>
	
	</form>
	
</div>