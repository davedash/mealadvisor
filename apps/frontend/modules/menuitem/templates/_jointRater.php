<?php use_helper('Rating');?>
<?php echo joint_rater(array('object' => $menuitem, 'user' => $sf_user, 'update'=>$menuitem->getStrippedTitle().'_rating', 'message'=>null, 'module'=>'menuitem')) ?>