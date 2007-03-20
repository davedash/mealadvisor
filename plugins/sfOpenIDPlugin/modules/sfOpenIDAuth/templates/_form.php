<?php echo form_tag('@openid_signin') ?>

<fieldset>
	<ol>
		<li><?php echo input_tag('openid_url') ?>
		</li>
	</ol>

</fieldset>
<p class="submit">
	<?php if (isset($referer)): ?>
	<?php echo input_hidden_tag('referer', $referer) ?>
	<?php endif ?>		
	<?php echo submit_tag('login') ?>
</p>
</form>