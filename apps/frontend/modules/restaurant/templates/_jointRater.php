<?php use_helper('Rating');?>
<?php echo joint_rater(array('object' => $restaurant, 'user' => $sf_user, 'update'=>$restaurant->getStrippedTitle().'_rating', 'message'=>null, 'module'=>'restaurant')) ?>
<?php // THIS IS INCOMPLETE ... ?>