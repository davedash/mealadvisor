<?php
/* Copyright 2006 Sxip Identity */
if (!defined('WHOBAR')) die("Please do not access directly");
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once("infocard/infocard-print-binary.php");
require_once("infocard/infocard-post-decrypt.php");
require_once("infocard/infocard-post-get-claims.php");
require_once("infocard/infocard-pkey-get-public.php");

if ( !defined('WHOBAR_TEMP_DIR') ) {
    $tmp = ini_get('upload_tmp_dir');
    if (!isset($tmp) or !strlen($tmp))
        $tmp = '/tmp';
    define('WHOBAR_TEMP_DIR', "$tmp/whobar");
}
function whobar_discover($request, $return_urls, $trust_root=NULL) {
    session_start();
    $result = array('whobar_errno'=>1, 'whobar_errmsg'=>"I couldn't authenticate you with the given data, sorry.");
    $invoke_infocard = $request['whobar_invoke_infocard'];
    unset($request['whobar_invoke_infocard']);
    whobar_set_session_request($request);
    if (isset($invoke_infocard)) {
    ?>
<html>
<head>
<title>One moment please...</title>
<script type="text/javascript">
    function autoSubmit() {
        document.getElementById("whobarForm").submit();
    }
 </script>
</head>
<body onload="autoSubmit()">
    <form action="<?php echo htmlentities($return_urls['infocard']); ?>" id="whobarForm" method="post">
    <object type="application/x-informationCard" name="xmlToken" ID="cardspaceObject">
      <param Name="issuer" Value="http://schemas.xmlsoap.org/ws/2005/05/identity/issuer/self" />
      <param Name="tokenType" Value="http://docs.oasis-open.org/wss/oasis-wss-saml-token-profile-1.1#SAMLV1.1" />
      <?php 
            $properties = whobar_map_attributes_for_form(whobar_get_attributes_from_request($request), 'infocard');
            echo "      <param Name=\"requiredClaims\" Value=\"" . implode(" ", array_values($properties)) . "\" />\n";
      ?>
    </object>
    <noscript>
        <input type="submit" value="Continue" />
        Click 'Continue' to login.  You are only seeing this because you appear to have JavaScript disabled.
    </noscript>
    </form>
</body>
</html>
    <?php
        exit(0);
    }
    elseif (!isset($request['openid_identifier']) or !strlen($request['openid_identifier'])) {
        $result['whobar_errno'] = 2;
        $result['whobar_errmsg'] = 'Please enter an identifier';
        return $result;
    }
    else {
        // OpenID discovery
        $http_response = null;
        $store = new Auth_OpenID_FileStore(WHOBAR_TEMP_DIR);
        $consumer = new Auth_OpenID_Consumer($store);

        // Begin the OpenID authentication process.
        $auth_request = $consumer->begin($request['openid_identifier']);

        // Handle failure status return values. - sucks that the OpenID libs don't give better error messages.
        if (!$auth_request) {
            return $result;
        }

        // Redirect the user to the OpenID server for authentication.  Store
        // the token for this authentication so we can verify the response.
        if (!isset($trust_root))
            $trust_root = preg_replace('#(.*)/.*#','\1', $return_urls['openid']);
        $redirect_url = $auth_request->redirectURL($trust_root,
                                                   $return_urls['openid']);
        error_log("Location: ".$redirect_url);
        header("Location: ".$redirect_url);
        exit(0);
    }
}

function whobar_verify($type, $params = array()) {
    session_start();
    $result = array( 'whobar_errno' => 0);
    $attributes = array();
    switch($type) {
        case 'infocard';
            ini_set('register_long_arrays', TRUE);

            $token = "";
            
            // The following can be uncommented for debugging
            // $_POST["canonicalToken"] = TRUE;
            // $_POST["canonicalSignedInfo"] = TRUE;
            // $_POST["parsedVariables"] = TRUE;

            do {
                // Checking for people who don't know you need to use https at this point
                if (strncmp("https:", $_SERVER["HTTP_REFERER"], 5) != 0){
                    $result['whobar_errmsg'] = "InfoCard currently must be invoked from an https protected page";
                    $result['whobar_errno'] = 16;
                    break;
                }

                // Checking to see if a token was produced
                if (!array_key_exists("xmlToken", $_POST)){
                    $result['whobar_errmsg'] = "Login was cancelled, or InfoCard was not available.";
                    $result['whobar_errno'] = 16;
                     break;
                }

                if (!$tokenContent = stripslashes($_POST["xmlToken"]))
                {
                     $result['whobar_errmsg'] = "No xml token";
                     $result['whobar_errno'] = 16;
                     break;
                }

                // this can be set for testing signature validation interop
                if (array_key_exists("decrypted", $_POST) == FALSE){
                    // Decrypting the token
                    $error=infocard_post_decrypt($tokenContent, $token, $params);
                    if ($error != NULL)
                    {
                        $result['whobar_errmsg'] = "infocard_post_decrypt returned $error";
                        $result['whobar_errno'] = 16;
                        break;
                    }
                }
                else{
                    $token = $tokenContent;
                }
         
                // Checking the signature of what's inside - and getting the claims
                if ($error = infocard_post_get_claims($token, $attributes))
                {
                    $result['whobar_errmsg'] = "infocard_post_get_claims returned $error";
                    $result['whobar_errno'] = 16;
                    break;
                }
            } while (0);

        break;
        case 'openid';
            $store = new Auth_OpenID_FileStore(WHOBAR_TEMP_DIR);
            $consumer = new Auth_OpenID_Consumer($store);
            $response = $consumer->complete($_GET);
            if ($response->status == Auth_OpenID_CANCEL) {
                // This means the authentication was cancelled.
                $result['whobar_errmsg'] = 'Verification cancelled.';
                $result['whobar_errno'] = 16;
            } else if ($response->status == Auth_OpenID_FAILURE) {
                $result['whobar_errmsg'] = "OpenID authentication failed: " . $response->message;
                $result['whobar_errno'] = 16;
            } else if ($response->status == Auth_OpenID_SUCCESS) {
                // This means the authentication succeeded.
                $identifier_key = 'identifier';
                foreach (whobar_get_attributes_from_request(whobar_get_session_request()) as $k => $v) {
                    if ($v == "http://openid.net/schema/person/guid") {
                        $identifier_key = $k;
                        break;
                    }
                }
                $attributes[$identifier_key] = $response->identity_url;
            }
        break;
        default;
            $result['whobar_errmsg'] = "I don't know $type";
            $result['whobar_errno'] = 1;
        break;
    }
    if (!$result['whobar_errno']) {
        $result = array_merge(
            whobar_map_attributes_for_site(
                $attributes, 
                $_REQUEST['whobar_proto'], 
                whobar_get_attributes_from_request(whobar_get_session_request())),
            $result
        );
    }
    return $result;
}
?>
