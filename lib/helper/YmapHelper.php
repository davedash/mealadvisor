<?php

use_helper('Javascript');

function ymap_overlay_rss($rss) {
	$rss = url_for($rss, true);
	return javascript_tag("ymap.overlayRSS('$rss')");
}

?>