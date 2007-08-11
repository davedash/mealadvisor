<?php echo fb_form_tag('@openid_signin') ?>
	<fieldset>
		<div class="form-row">
			<label for="openid_url">OpenID</label>
			<?php echo input_tag('openid_url',$sf_request->getParameter('openid_url'),'class=openid') ?>
		</div>
	</fieldset>
	<?php echo submit_tag('authenticate') ?>
</form>

<?php echo $sf_user->getAttribute('id','not found', 'facebook') ?>
