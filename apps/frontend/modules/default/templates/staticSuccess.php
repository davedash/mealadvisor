<?php require_once('markdown.php')// use_helper('MyText');?>
<?php echo markdown(file_get_contents(dirname(__FILE__) . '/' . $page.'.markdown'));?>