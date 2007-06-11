<?php use_helper('Validation') ?>
<h2>Login</h2>

<p>If you would like to be a reviewer <?php echo link_to('sign up now', '@register') ?>!</p>
<?php echo form_tag('@sf_guard_signin') ?>

  <fieldset>

  <div class="form-row">
    <?php echo form_error('username') ?>
    <label for="nickname">username:</label>
    <?php echo input_tag('username', $sf_params->get('username')) ?>
  </div>

  <div class="form-row">
    <?php echo form_error('password') ?>
    <label for="password">password:</label>
    <?php echo input_password_tag('password') ?>
  </div>

  </fieldset>

  <?php echo submit_tag('sign in') ?>
  
</form>

<h2>Open Id sign-in</h2>

<?php echo form_tag('@openid_signin') ?>
	<fieldset>
		<div class="form-row">
			<label for="openid_url">OpenID</label>
			<?php echo input_tag('openid_url',$sf_request->getParameter('openid_url'),'class=openid') ?>
		</div>
	</fieldset>
	<?php echo submit_tag('authenticate') ?>
</form>