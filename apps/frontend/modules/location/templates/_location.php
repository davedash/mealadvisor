<?php use_helper('MyText');?>
<p>
<?php echo brif($location->getAddress()) ?>
<?php echo brif(textif($location->getCity(), ', ') . textif($location->getState(), ' ') . $location->getZip()) ?>
<?php echo brif($location->getCountry(),$location->getCountry()->__toString()) ?>
<label for="phone">Phone:</label> <span id="phone"><?php echo format_phone($location->getPhone()) ?></span>
</p>