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
<body>
  
  <div id="doc4"> 
    <div id="hd">
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
      <!-- header -->
    </div>  
    <div id="bd">b<!-- body --></div>  
    <div id="ft">f<!-- footer --></div>  
  </div>
  
  ... end of new yui code... begining of old crud
	<div id="indicator" style="display: none"></div>

		<div id="header">
			<div id="mini_logo"><?php echo logo_tag() ?></div>
			<div class="ad" id="link_unit_1">
			</div>

			<?php if ($sf_user->isLoggedIn()): ?>
			<div id="welcome">Welcome <?php echo link_to_user($sf_user) ?></div>
			<div id="corner_pic">=)</div>
			<?php endif ?>

			<div id="top_menu">
				<?php if ($sf_user->isLoggedIn()): ?>
				<?php echo link_to('logout', '@sf_guard_signout') ?>
				<?php else: ?>
				<?php echo link_to('login', '@sf_guard_signin')  ?> 
				| <?php echo link_to('register' , '@register') ?>
				<?php endif ?>
				| <?php echo link_to('add restaurant', '@restaurant_add') ?>
				| <?php echo link_to_function('search', visual_effect('toggle_blind', 'search_bar')) ?>
			</div>
		</div>

		<div id="search_bar" style="display:none">
			<?php echo include_partial('restaurant/search') ?>
			<p><?php echo link_to_function('cancel', visual_effect('toggle_blind', 'search_bar')) ?></p>
		</div>	


		<?php if($sf_flash->has('notice')): ?>
		<?php foreach ($sf_flash->get('notice') as $key=>$notice): ?>
		<p class="notice" id="notice_<?php echo $key ?>"><?php echo $notice ?></p>
		<?php echo javascript_tag(visual_effect('fade', 'notice_'.$key,array('duration'=>'5'))) ?>
		<?php endforeach ?>
		<?php endif ?>


		<?php if (empty($hideLogin)): ?>
		<div id="login" style="display: none">
			<h2>sign in</h2>
			<?php echo form_tag('user/localLogin') ?>
			<?php echo input_hidden_tag('referer', $sf_params->get('referer') ? $sf_params->get('referer') : $sf_request->getUri()) ?>
			<label for="username">username</label><?php echo input_tag('username') ?>
			<label for="password">password</label><?php echo input_password_tag('password') ?>
			<?php echo submit_tag('login') ?>
			<br/>
			<?php echo link_to_function('use openID', visual_effect('blind_up', 'login', array('duration' => 0.5)) . 
			visual_effect('blind_down', 'login_openid', array('duration' => 0.5))) ?>

			<?php echo link_to_function('cancel', visual_effect('blind_up', 'login', array('duration' => 0.5))) ?>

			</form>
		</div>
		<div id="login_openid" style="display: none">
			<h2>sign in with openID</h2>
			<?php echo form_tag('@sf_guard_signin', 'id=loginform') ?>
			OpenID: <?php echo input_tag('openid_url',null,'class=openid')?>
			<?php echo input_hidden_tag('referer', $sf_params->get('referer') ? $sf_params->get('referer') : $sf_request->getUri(), 'id=referer_openid') ?>
			<?php echo submit_tag('login') ?>
			<?php echo link_to_function('cancel', visual_effect('blind_up', 'login_openid', array('duration' => 0.5))) ?>

			<br/><small><em>e.g. <em>username</em>.livejournal.com</em></small>
			</form>
		</div>	  
		<?php endif ?>


		<div id="main">	
			<?php echo $content ?>
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
