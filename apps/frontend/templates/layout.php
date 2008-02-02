<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>
	<?php echo include_title() ?>
	<?php echo auto_discovery_link_tag('rss', 'feed/latest', array('title' => 'Latest restaurants'))?> 	
	<?php echo auto_discovery_link_tag('rss', '@feed_latest_georss',
	array('title' => 'Latest Restaurants\' Locations (GeoRSS)' ))?> 	
	
	<?php echo include_feeds() ?>
	
	<link rel="shortcut icon" href="/images/g2/logo/favicon.png" />

</head>
<body class="yui-skin-sam">
  
  <div id="doc4" class="incomplete"> 
    <div id="hd" class="incomplete">
      <div id="header">
        <h1>
          <?php echo link_to('meal advisor', '/') ?>
        </h1>
        <div id="search_box">
          <?php echo form_tag('@search_restaurant', 'id=search_form') ?>
            <?php echo input_tag('q', htmlspecialchars($sf_params->get('search','Search for food')), 'class=text') ?>
            &nbsp;
            <?php echo submit_tag('search', 'class=small') ?>
            <span class="info">
              e.g. "Amber Cafe near Mountain View"
            </span>
          </form>
        </div>
      </div>
      <div id="top_menu" class="incomplete">
        <ul>
          <li>
            <?php echo link_to('Review a meal', 'meal/review') ?>
          </li>
          <?php if ($sf_user->isLoggedIn()): ?>
          
          <li>
            <?php echo link_to('logout', '@sf_guard_signout') ?>
          </li>
          
          <?php else: ?>
            <li>
              <?php echo link_to('Sign In', '@sf_guard_signin') ?>
            </li>
            <li>
              <?php echo link_to('Sign Up', '@register') ?>
            </li>
            <?php endif ?>
            <li>
              <?php echo link_to('About', '/about') ?>
            </li>
        </ul>
      </div>
      <!-- header -->
    </div>  
    <div id="bd"><!-- body -->
      <?php echo $sf_data->getRaw('sf_content') ?>
    </div>  

    <div id="ft">f<!-- footer --></div>  
  </div>
  
  ... end of new yui code... begining of old crud
  <p>View <?php echo link_to('all restaurants', '@restaurant_list') ?> 
  <?php echo rss_link_to('feed/freshest') ?>.</p>
		<div id="main">	
			<div id="rss_info">
				<ul>

					<li><?php echo include_partial('default/feed', array('title' => 'Latest restaurants', 'url' => 'feed/latest'));?></li>

					<?php foreach(get_feeds() AS $feed): ?>
					<li><?php echo include_partial('default/feed',$feed);?></li>

					<?php endforeach?>
				</ul>
			</div>
		</div>

		<?php if (empty($footerLogoOff)): ?>
		<?php echo include_partial('default/footer');?>
		<?php endif ?>

		<div id="ad_unit_1" class="ad">
			<script type="text/javascript"><!--
			google_ad_client = "pub-2985200403633756";
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "text";
			google_ad_channel ="1642910453";
			google_color_border = "222222";
			google_color_bg = "222222";
			google_color_link = "307abf";
			google_color_url = "307abf";
			google_color_text = "eeeeee";
			//--></script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</div>	
		<div id="footer">
			<?php echo link_to('google co-op', 'http://google.com/coop/profile?user=015173080624703800226') ?>
			| <?php echo link_to('blog', 'http://yumbo.reviewsby.us/') ?>
			| <?php echo link_to('about' , '@about') ?>
			|
			<?php echo link_to('contact us','@feedback') ?>
		</div>
	</div>
	
	<!-- urchin	-->
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-332807-1";
	urchinTracker();
	</script>
	<!-- /urchin -->

</body>
</html>
