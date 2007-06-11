<ul>
<?php foreach ($restaurant->getLocations() as $location): ?>
  <li>
		<?php echo $location->toLargeString() ?>
		<?php echo link_to('delete', 'location/delete?id='.$location->getId(),'confirm=wtf?') ?>	
	</li>
<?php endforeach ?>
</ul>
<hr/>
<h2>We found the following via Yahoo Local</h2>
<div id="yl_results">
	<ul>
	<li><?php echo link_to_remote('get results', array('url' => 'restaurant/yl_results?q='.$restaurant->getName().'&l=United States&p=1', 'update' => 'yl_results', )) ?></li>

	<li><?php echo link_to_remote('get results near MSP', array('url' => 'restaurant/yl_results?l=55408&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li></ul>

	<li><?php echo link_to_remote('get results near Nashville', array('url' => 'restaurant/yl_results?l=nashville,tn&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li></ul>

	<li><?php echo link_to_remote('get results near SFO', array('url' => 'restaurant/yl_results?l=san francisco,ca&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li></ul>

	<li><?php echo link_to_remote('get results near Honolulu, HI', array('url' => 'restaurant/yl_results?l=honolulu,hi&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li></ul>

	<li><?php echo link_to_remote('get results near Hilo, HI', array('url' => 'restaurant/yl_results?l=hilo,hi&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li>
	<li><?php echo link_to_remote('get results near Kailua Kona, HI', array('url' => 'restaurant/yl_results?l=kailua kona,hi&q='.$restaurant->getName().'&p=1', 'update' => 'yl_results', )) ?></li></ul>
</div>