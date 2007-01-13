<?php echo form_tag('@search_restaurant', 'id=search_form') ?>    
  <label for="search">Find</label>
  <?php echo input_tag('search', htmlspecialchars($sf_params->get('search')), array('class' => 'text')) ?>&nbsp;
	<label for="location">near</label>
	&nbsp; <?php echo input_tag('location',$sf_params->get('location',$sf_user->getLocation()), array('class' => 'text')) ?>
  <?php echo submit_tag('go', 'class=small') ?>
</form>
