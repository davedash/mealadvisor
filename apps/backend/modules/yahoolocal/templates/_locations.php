<ul>
<?php foreach ($restaurant->getLocations() as $location): ?>
  <li><?php echo $location->toLargeString() ?></li>
<?php endforeach ?>
</ul>