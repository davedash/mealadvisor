<?php use_helper('Javascript');?>
Checked items will be added!
<ol>
<?php foreach ($results as $result): ?>
  <li>
		<?php if (LocationPeer::retrieveByDataSourceKey(Location::YAHOO_LOCAL, $result['id'])): ?>
		  -
		<?php else:?>
		<?php echo checkbox_tag('yid[]',$result['id'],true) ?>
		<?php endif ?>
	
		
		<?php echo $result->Title ?>: 
		<?php echo $result->Address ?> <?php echo $result->City ?>, <?php echo $result->State ?>
	</li>
<?php endforeach ?>
</ol>


<?php echo link_to_remote('more', array('url' => 'restaurant/yl_results?l='.$location.'&q='.$restaurant.'&p='.($page+1), 'update' => 'yl_results' )) ?>
