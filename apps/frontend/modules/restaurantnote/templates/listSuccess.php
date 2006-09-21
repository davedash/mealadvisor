<?php
// auto-generated by sfPropelCrud
// date: 02/24/2006 22:29:34
?>
<h1>restaurantnote</h1>

<table>
<thead>
<tr>
  <th>Id</th>
  <th>User</th>
  <th>Note</th>
  <th>Restaurant</th>
  <th>Location</th>
  <th>Updated at</th>
  <th>Created at</th>
</tr>
</thead>
<tbody>
<?php foreach ($restaurant_notes as $restaurant_note): ?>
<tr>
    <td><?php echo link_to($restaurant_note->getId(), 'restaurantnote/show?id='.$restaurant_note->getId()) ?></td>
      <td><?php echo $restaurant_note->getUserId() ?></td>
      <td><?php echo $restaurant_note->getNote() ?></td>
      <td><?php echo $restaurant_note->getRestaurantId() ?></td>
      <td><?php echo $restaurant_note->getLocationId() ?></td>
      <td><?php echo $restaurant_note->getUpdatedAt() ?></td>
      <td><?php echo $restaurant_note->getCreatedAt() ?></td>
  </tr>
<?php endforeach ?>
</tbody>
</table>

<?php echo link_to ('create', 'restaurantnote/edit') ?>
