<?php

require_once 'config.php';

if (function_exists('getOpenIDStore') && isset($openid_users)) {
    require_once 'lib/session.php';
    require_once 'lib/actions.php';

    init();

    $action = getAction();
    if (!function_exists($action)) {
        $action = 'action_default';
    }

    $resp = $action();

    writeResponse($resp);
} else {
?>
<html>
  <head>
    <title>PHP OpenID Server</title>
    <body>
      <h1>PHP OpenID Server</h1>
      <p>
        This server needs to be configured before it can be used. Edit
        <code>config.php</code> to reflect your server's setup, then
        load this page again.
      </p>
    </body>
  </head>
</html>
<?php
}
?>