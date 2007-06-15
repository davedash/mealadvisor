<fb:subtitle>
  <fb:userlink uid="<?php echo $profile->getFacebookUserid() ?>" />'s latest 
    meal reviews.
</fb:subtitle>
<?php include_component('sfFacebook','reviews', array('profile' => $profile )); ?>
