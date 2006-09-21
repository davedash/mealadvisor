<?php use_helper('MenuItem');?>
<?php if (tags_for_menuitem($menu_item)): ?>

<?php echo image_tag('tag.png', 'alt=Popular Tags') ?> <?php echo tags_for_menuitem($menu_item) ?>
<?php endif ?>
