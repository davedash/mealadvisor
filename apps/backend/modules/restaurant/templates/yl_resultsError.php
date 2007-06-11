<?php use_helper('Javascript');?>
No results.  

<?php echo link_to_remote('try again', array('url' => 'restaurant/yl_results?q='.$restaurant.'&p='.$page, 'update' => 'yl_results', )) ?>
