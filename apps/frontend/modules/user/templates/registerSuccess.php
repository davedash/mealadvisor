<?php use_helper('Javascript', 'Validation');?>
<p>
	We support 
	<?php echo link_to_function('openID', visual_effect('blind_down', 'login_openid', array('duration' => 0.5))) ?> so if you
	have a livejournal or other openID account, you don't need to register.
</p>

<?php echo form_tag('@register') ?>
	<?php echo form_error('username') ?>
	
	<label for="username">username</label><?php echo input_tag('username') ?>
	<br/>
	<?php echo form_error('password') ?>
	<label for="password">password</label><?php echo input_password_tag('password') ?>
	<?php echo form_error('password_repeat') ?>

	<br/><label for="password_repeat">repeat password</label><?php echo input_password_tag('password_repeat') ?>
<br/>
    <?php echo submit_tag('register') ?>
	
	</form>
	
</form>