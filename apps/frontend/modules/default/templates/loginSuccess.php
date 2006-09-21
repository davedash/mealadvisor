<p>
	
	You'll need to sign in first.  If you don't have an account, <?php echo link_to('create one for free', '@register'); ?>.
	
</p>
<?php use_helper('Javascript');?>
<div id="login">
		<h2>sign in ...</h2>
		<?php echo form_tag('user/localLogin') ?>
		  <?php echo input_hidden_tag('referer', $sf_params->get('referer')) ?>
		<label for="username">username</label><?php echo input_tag('username') ?>
		<label for="password">password</label><?php echo input_password_tag('password') ?>
		<?php echo submit_tag('login') ?>
		<br/>


	</form>
	</div>
	<div id="login_openid">
		<h2>... or sign in with openID</h2>
		<?php echo form_tag('@login', 'id=loginform') ?>
		OpenID: <?php echo input_tag('openid_url',null,'class=openid')?>
		<?php echo input_hidden_tag('referer', $sf_params->get('referer'), 'id=referer_openid') ?>
		<?php echo submit_tag('login') ?>

		<br/><small><em>e.g. <em>username</em>.livejournal.com</em></small>
	</form>
</div>