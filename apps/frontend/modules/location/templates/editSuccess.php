<?php use_helper('Object') ?>

<h2><?php if (!$location->getId()): ?>Add <?php endif ?>
Location for <?php echo $restaurant ?></h2>

<?php echo form_tag('@location_add?restaurant='.$restaurant->getStrippedTitle() ,'id=add_location') ?>

<table>
<tbody>
<tr>
  <th>Name:</th>
  <td><?php echo object_input_tag($location, 'getName', array (
  'size' => 20,
)) ?> (If something other than <em>City, State</em> e.g. <em>Southdale Mall</em>)</td>
</tr>
<tr>
  <th>Address:</th>
  <td><?php echo object_input_tag($location, 'getAddress', array (
  'size' => 20,
)) ?></td>
</tr>
<tr>
  <th>City:</th>
  <td><?php echo object_input_tag($location, 'getCity', array (
  'size' => 20,
)) ?></td>
</tr>
<tr>
  <th>State:</th>
  <td><?php echo object_input_tag($location, 'getState', array (
  'size' => 20,
)) ?></td>
</tr>

<tr>
  <th>Zip:</th>
  <td><?php echo object_input_tag($location, 'getZip', array (
  'size' => 7,
)) ?></td>
</tr>
<tr>
  <th>Country:</th>
  <td>
	<?php echo object_select_tag($location, 'getCountry', array('related_class' => 'Country', 'include_blank' => true))?>
</td>
</tr>

<tr>
  <th>Phone:</th>
  <td><?php echo object_input_tag($location, 'getPhone', array (
  'size' => 10,
)) ?></td>
</tr>
</tbody>
</table>
<hr />
<?php echo submit_tag('save') ?>

</form>
