<?php echo form_tag('@whobar') ?>
<form class="openid" method="post" action="../index.php?whobar_action=discover" accept-charset="utf-8" id="whobarForm">
	<p>
		<label for="openid_identifier">
			<a href="#" onclick="identifierHelp('id_in')" title="What is an i-name?" id="id_in">i-name</a> | 
			<a href="#" onclick="identifierHelp('id_oi')" title="What is an OpenID?" id="id_oi">OpenID</a>
		</label>  
		<p id="helpArea"></p>

		<input type="hidden" name="openid.ns.ax" value="http://openid.net/srv/ax/1.0" />
		<input type="hidden" name="openid.ax.fetch.first_name" value="http://openid.net/schema/namePerson/first" />
		<input type="hidden" name="openid.ax.fetch.last_name" value="http://openid.net/schema/namePerson/last" />
		<input type="hidden" name="openid.ax.fetch.email" value="http://openid.net/schema/contact/internet/email" />
		<input type="hidden" name="openid.ax.fetch.identifier" value="http://openid.net/schema/person/guid" />

		<input type="hidden" name="a_passthrough_parameter" value="an example passthrough parameter" />

		<input name="openid_identifier" size="25" id="openid_identifier" value="" />
		<?php echo submit_tag('login') ?>
	</p>
</form>