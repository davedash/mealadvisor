<?php use_helper('MenuItem');?>
<?php echo include_partial('tag/menuitem_tags', array('menu_item' => $menu_item)) ?>
<?php if ($sf_user->isLoggedIn() && !empty($add)): ?>
<?php if (tags_for_menuitem_from_user($menu_item, $sf_user->getUser())): ?>
<br/>Your Tags: <?php echo tags_for_menuitem_from_user($menu_item, $sf_user->getUser()) ?>
<?php endif ?>
  

<?php endif ?>
