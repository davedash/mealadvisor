<style>
  .reviews li
  {
    margin: 0;
    padding: 0;
    list-style: none;
    clear: left;
  }
  .reviews .image {
    float: left;
    width: 80px;
  }
  
  .reviews h2 {
    font-size: 1em;
  }
</style>

<?php if (count($comments)): ?>
<ul class="reviews">
    
  <?php foreach ($comments as $comment): ?>
  <li>
    <div class="image">
      <?php echo link_to_menuitem($comment->getMenuItem(), 'absolute=true',
      image_for_item($comment->getMenuItem(), 'longest_side=75 absolute=true')
      ) ?>
    </div>
    
    <h2>
      <?php echo link_to_restaurant($comment->getRestaurant(), true) ?>: 
      <?php echo link_to_menuitem($comment->getMenuItem(), 'absolute=true') ?></h2>
    <div>
      <?php echo $comment->getNote() ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php else:?>
No reviews
<?php endif ?>
