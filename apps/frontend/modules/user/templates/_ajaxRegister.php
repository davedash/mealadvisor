<?php use_helper('Validation','Javascript');?>

<h2>Register to review food!</h2>

<p>
	We support 
	openID so if you
	have a LiveJournal or other openID account, you can 
	<?php echo link_to_function('sign-in without registering', 'LoginDialog.showOpenID()') ?>.
</p>

<p>
	After registering this page will refresh.
</p>

<?php echo form_remote_tag('url=@ajax_register update=login_dlg_register script=true', 'class=medium') ?>

	<fieldset>

		<ol>
			<li>
				<?php echo form_error('username') ?>
				<label for="username">Username:</label>
				<?php echo input_tag('username') ?>
			</li>
			<li>
				<?php echo form_error('password') ?>
				<label for="password">Password:</label>
				<?php echo input_password_tag('password') ?>
			</li>
			<li>
				<label for="password_repeat">Repeat:</label>
				<?php echo input_password_tag('password_repeat') ?>
			</li>
		</ol>
	</fieldset>

	<p class="submit_line">
		<?php echo input_hidden_tag('referer', $sf_request->getParameter('referer')) ?>
		<?php echo submit_tag('sign in') ?>
  </p>
</form>