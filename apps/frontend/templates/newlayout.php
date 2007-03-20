<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-200000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>

	<?php echo include_title() ?>
	<?php echo auto_discovery_link_tag('rss', 'feed/latest', array('title' => 'Latest restaurants'))?> 	
	<?php echo auto_discovery_link_tag('rss', '@feed_latest_georss',
	array('title' => 'Latest Restaurants\' Locations (GeoRSS)' ))?> 	
	
	<?php echo include_feeds() ?>
	
	<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>

	<div id="indicator" style="display: none"></div>

	
	<div id="doc2">
		<div id="hd">
			<h1 id="mini_logo"><?php echo logo_tag() ?></h1>
			
			<div class="ad" id="link_unit_1">
				<?php tla_ads() ?>
			</div>

			<?php if ($sf_user->isLoggedIn()): ?>
			<div id="welcome">Welcome <?php echo link_to_user($sf_user) ?></div>
			<div id="corner_pic">=)</div>
			<?php endif ?>

			<ul id="top_menu">
				<?php if ($sf_user->isLoggedIn()): ?>
				<li><?php echo link_to('logout', '@sf_guard_signout') ?></li>
				<?php else: ?>
				<li><?php echo link_to('login/register', '@sf_guard_signin', 'id=login_button onclick=return false')  ?></li> 
				<!-- <li><?php echo link_to('register', '@register') ?></li> -->
				<?php endif ?>
				<li><?php echo link_to('add restaurant', '@restaurant_add') ?></li>
				<li><?php echo link_to_function('search', visual_effect('toggle_blind', 'search_bar')) ?></li>
			</ul>
			
			<div id="search_bar" style="display:none">
				<?php echo include_partial('restaurant/search') ?>
				<p><?php echo link_to_function('cancel', visual_effect('toggle_blind', 'search_bar')) ?></p>
			</div>	
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

	<div id="bd"><!-- body -->

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
	<div id="ft"><!-- footer -->
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
	<!-- click tracking -->
	<script type="text/javascript"> 
	function asClick(){if (window.status.indexOf('go to') == 0) { 
			urchinTracker('/adsense/ads/' + escape(window.status.substring(6))); 
		} 
		else if (window.status.indexOf('View ads about') == 0) { 
			urchinTracker('/adsense/links/' + escape(window.status.substring(15))); 
		} else {
			urchinTracker('/adsense/ads/' + escape(window.status));
		}
	} 
	var elements; 
	if (document.getElementsByTagName) 
	{ 
		elements = document.body.getElementsByTagName("IFRAME"); } else if (document.body.all) { elements = document.body.all.tags("IFRAME"); } else { elements = Array(); } for (var i = 0; i < elements.length; i++) { if (elements[i].src.indexOf('googlesyndication.com') > -1) { elements[i].onfocus = asClick(); } }
	</script>
	<!-- /click tracking -->
	<?php echo javascript_tag( nifty_round_elements( "#hd") ) ?>
	
		  <div id="login-dlg" style="visibility:hidden;">
		    <div class="x-dlg-hd">Sign-in to <?php echo link_to('reviewsBy.us','@homepage') ?></div>

		    <div class="x-dlg-bd">
			    <div id="login_dlg_standard" class="x-layout-inactive-content" style="padding:10px;">
						<?php include_partial('sfGuardAuth/ajaxLogin', array());?>
			    </div>
			    <div id="login_dlg_openid" class="x-layout-inactive-content" style="padding:10px;">
						<h2>Sign in with openID</h2>
						<?php include_partial('sfOpenIDAuth/form', array('referer' => url_for(sfRouting::getInstance()->getCurrentInternalUri(), true)));?>
			    </div>
			    <div id="login_dlg_register" class="x-layout-inactive-content" style="padding:10px;">
						<?php include_partial('user/ajaxRegister', array());?>
			    </div>
		    </div>
		</div>
</body>
</html>
