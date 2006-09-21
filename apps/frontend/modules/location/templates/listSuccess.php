<?php
// auto-generated by sfPropelCrud
// date: 04/02/2006 10:47:56
?>
<h1>location</h1>

<table>
<thead>
<tr>
  <th>Id</th>
  <th>Restaurant</th>
  <th>Name</th>
  <th>Address</th>
  <th>City</th>
  <th>State</th>
  <th>Zip</th>
  <th>Phone</th>
  <th>Approved</th>
  <th>Updated at</th>
  <th>Created at</th>
</tr>
</thead>
<tbody>
<?php foreach ($locations as $location): ?>
<tr>
    <td><?php echo link_to($location->getId(), 'location/show?id='.$location->getId()) ?></td>
      <td><?php echo $location->getRestaurantId() ?></td>
      <td><?php echo $location->getName() ?></td>
      <td><?php echo $location->getAddress() ?></td>
      <td><?php echo $location->getCity() ?></td>
      <td><?php echo $location->getState() ?></td>
      <td><?php echo $location->getZip() ?></td>
      <td><?php echo $location->getPhone() ?></td>
      <td><?php echo $location->getApproved() ?></td>
      <td><?php echo $location->getUpdatedAt() ?></td>
      <td><?php echo $location->getCreatedAt() ?></td>
  </tr>
<?php endforeach ?>
</tbody>
</table>

<?php echo link_to ('create', 'location/edit') ?>
