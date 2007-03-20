<h2>Login</h2>
<p>
	After logging in this page will refresh.
</p>
	

<?php use_helper('Validation','Javascript');?>
<?php echo form_remote_tag('url=@ajax_login update=login_dlg_standard script=true', 'class=medium') ?>

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
		</ol>
	</fieldset>

	<p class="submit_line">
		<?php echo input_hidden_tag('referer', $sf_request->getParameter('referer')) ?>
		<?php echo submit_tag('sign in') ?>
  </p>
</form>