<?php if ($sf_user->isAnonymous()): ?>
<p>
  Please 
  <?php echo link_to('link your Facebook profile to your reviewsby.us profile','@sfFacebook_confirm','absolute=true') ?>.  
  This will allow us to share your eating habits with your friends on Facebook.
</p>  
<?php else: ?>
<h1>Your reviews</h1>

<?php include_component('sfFacebook', 'reviews', array('profile' => $sf_user->getProfile() )) ?>

<?php endif ?>
