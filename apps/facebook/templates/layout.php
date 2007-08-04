<?php include_partial('sfFacebook/css');?>


<fb:dashboard>
  <?php echo fb_action('My Dining', '@homepage') ?>
</fb:dashboard>

<div class="app_content">
<?php echo $sf_data->getRaw('sf_content') ?>
</div>
