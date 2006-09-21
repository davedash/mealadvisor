<?php use_helper('Global');?>

<div class="review_block" id="comment_<?php echo $comment->getId() ?>">  
	<p class="author"><?php echo link_to_user($comment->getUser()) ?> - <?php echo $comment->getCreatedAt('%d %B %Y') ?></p>
	<div class="review_text" id="review_text_<?php echo $comment->getId()?>"><?php echo $comment->getHtmlNote() ?></div>
</div>
<?php if ($comment->getUser() && $comment->getUserId() == $sf_user->getId() && time() < 181 + $comment->getCreatedAt(null) ): ?>

<?php
if (empty($module)) {
	$module = 'restaurantnote';
}

?>

<script type="text/javascript">
//<![CDATA[

	makeEditable('<?php echo $comment->getId() ?>', "<?php echo url_for($module . '/save?id=' . $comment->getId()) ?>", "<?php echo url_for($module . '/show?id=' . $comment->getId() . '&mode=raw') ?>", <?php echo 181-(time() - $comment->getCreatedAt(null)) ?>);
		
//]]>
</script>
<?php endif ?>

