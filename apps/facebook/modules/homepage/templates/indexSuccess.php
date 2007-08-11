<?php if($unlinked): ?>

<div class="box">
  <div class="header">
    <h2>Link your account?</h2>
  </div>
  <div class="content">
  <p>
    If you have an existing 
    <?php echo link_to('reviewsby.us', 'http://reviewsby.us/')  ?>
    account,  you can 

    <?php echo fb_link_to('link it to your facebook account', '@link_accounts', 'class=strong')?>.
  </p>
  <p>
    <?php echo link_to('No thanks', '#')?>.
  </p>
  </div>
</div>


<?php endif; ?>

test

<?php echo $sf_user->getAttribute('id','not found', 'facebook') ?>

<?php if ($sf_user->isAnonymous()): ?>
  Anon!
  <?php else: ?>
  <?php echo $sf_user->getUsername() ?>
<?php endif ?>

<?php echo fb_action('Home', '@homepage') ?>
