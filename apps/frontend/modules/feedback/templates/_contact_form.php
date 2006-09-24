<?php use_helper('Validation');?>
<?php echo form_tag(sfRouting::getInstance()->getCurrentInternalUri(true), 'id=feedback_error name=feedback class=stylized') ?>
<fieldset>
	<div class="required">
		  <?php echo form_error('from') ?>
		<label for="from">From</label> <?php echo input_tag('from', null, 'class=inputText') ?>
	</div>
	<div class="required">
		  <?php echo form_error('email') ?>
		<label for="email" >Email</label><?php echo input_tag('email') ?>
	</div>

	<div class="required">
		<label for="subject">Subject</label>
	
		<?php if (empty($subject)): ?>
		  <?php echo input_tag('subject') ?>
		<?php else: ?>
			<small><?php echo $subject ?></small>
			<?php echo input_hidden_tag('subject', $subject) ?>
		<?php endif ?>
	
	</div>
	<div class="required">
		<?php if (!empty($info)): ?>
			<label for="info">Current Info</label><small><?php echo $info ?></small>  
		<?php endif ?>
		<?php echo input_hidden_tag('info', htmlentities($info)) ?>
	</div>
	<div class="required">
		  <?php echo form_error('message') ?>
		<label for="message">Message</label>
	
		<?php echo textarea_tag('message') ?>
	</div>
</fieldset>

	<div class="submit">
		<?php echo submit_tag('send', 'class=inputSubmit') ?>
	</div>
</form>
