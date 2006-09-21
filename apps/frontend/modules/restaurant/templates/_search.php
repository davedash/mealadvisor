<?php echo form_tag('@search_restaurant', 'id=search_form') ?>    
  <?php echo input_tag('search', htmlspecialchars($sf_params->get('search')), array('style' => 'width: 150px')) ?>&nbsp;
  <?php echo submit_tag('search', 'class=small') ?>
  <?php echo checkbox_tag('search_all', 1, $sf_params->get('search_all')) ?>&nbsp;<label for="search_all" class="small">all words</label>
</form>
