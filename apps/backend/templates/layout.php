<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php echo include_http_metas() ?>
<?php echo include_metas() ?>

<?php echo include_title() ?>

<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>
<div id="doc3">
<div id="hd">
	<?php echo link_to('restaurant editor', 'restaurant') ?>
	<?php echo link_to('location editor', 'location') ?>
	<?php echo link_to('menu item editor', 'menuitem') ?>
	<?php echo link_to('yahoo local', 'yahoolocal') ?>
	
	
</div>
<div id="bd">
	<?php echo $sf_data->getRaw('sf_content') ?>
</div>
</div>


</body>
</html>
