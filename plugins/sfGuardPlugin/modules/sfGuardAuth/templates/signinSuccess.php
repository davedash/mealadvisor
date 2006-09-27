<?php use_helper('Validation') ?>

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

  <?php echo input_hidden_tag('referer', $sf_request->getParameter('referer')) ?>
  <?php echo submit_tag('sign in') ?>
  <?php echo link_to('Forgot your password?', '@sf_guard_password') ?>

</form>
