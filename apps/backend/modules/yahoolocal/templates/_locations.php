<?php use_helper('Javascript');?>
<ul>
<?php foreach ($restaurant->getLocations() as $location): ?>
  <li>
		<?php echo $location->toLargeString() ?>
		
		
		<?php echo link_to('delete', 'location/delete?id='.$location->getId(),'confirm=wtf?') ?>
		
		
			<?php echo link_to_remote(
				'replace',
				array(
					'url' => 'yahoolocal/replaceLocation?yid='.$yid.'&location='.$location->getId(), 
					'update' => 'locations_'.$restaurant->getId()
				)) ?>
	</li>
<?php endforeach ?>
</ul>