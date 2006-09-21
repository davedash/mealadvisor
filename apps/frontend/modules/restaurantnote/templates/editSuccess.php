<?php
// auto-generated by sfPropelCrud
// date: 02/24/2006 22:29:34
?>
<?php use_helper('Object') ?>

<?php echo form_tag('restaurantnote/update') ?>

<?php echo object_input_hidden_tag($restaurant_note, 'getId') ?>

<table>
<tbody>
<tr>
  <th>User:</th>
  <td><?php echo object_select_tag($restaurant_note, 'getUserId', array (
  'related_class' => 'User',
)) ?></td>
</tr>
<tr>
  <th>Note:</th>
  <td><?php echo object_textarea_tag($restaurant_note, 'getNote', array (
  'size' => '30x3',
)) ?></td>
</tr>
<tr>
  <th>Restaurant:</th>
  <td><?php echo object_select_tag($restaurant_note, 'getRestaurantId', array (
  'related_class' => 'Restaurant',
)) ?></td>
</tr>
<tr>
  <th>Location:</th>
  <td><?php echo object_select_tag($restaurant_note, 'getLocationId', array (
  'related_class' => 'Location',
)) ?></td>
</tr>
</tbody>
</table>
<hr />
<?php echo submit_tag('save') ?>
<?php if ($restaurant_note->getId()): ?>
  &nbsp;<?php echo link_to('delete', 'restaurantnote/delete?id='.$restaurant_note->getId(), 'post=true&confirm=Are you sure?') ?>
  &nbsp;<?php echo link_to('cancel', 'restaurantnote/show?id='.$restaurant_note->getId()) ?>
<?php else: ?>
  &nbsp;<?php echo link_to('cancel', 'restaurantnote/list') ?>
<?php endif ?>
</form>
