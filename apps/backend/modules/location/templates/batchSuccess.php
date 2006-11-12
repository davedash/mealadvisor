<?php use_helper('Object');?>
<h2>Add multiple restaurants for:</h2>

<?php echo form_tag('location/batch') ?>
<?php echo object_select_tag(new MenuItem(), 'getRestaurantId', array (
  'related_class' => 'Restaurant',
  'control_name' => 'restaurant_id',
)); ?><br />
<?php echo textarea_tag('batch',null,'size=80x24') ?>
<?php echo submit_tag() ?>
</form>