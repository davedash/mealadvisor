<?php echo form_tag('@search_restaurant', 'id=search_form') ?>    
  <?php echo input_tag('search', htmlspecialchars($sf_params->get('search')), array('style' => 'width: 100px')) ?>&nbsp;
	near
	&nbsp; <?php echo input_tag('location',$sf_params->get('location',$sf_params->get('location', $sf_user->getLocation())), array('style' => 'width:100px')) ?>
  <?php echo submit_tag('search', 'class=small') ?>
</form>
