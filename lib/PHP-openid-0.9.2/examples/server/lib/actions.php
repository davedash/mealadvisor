<?php

require_once "lib/common.php";
require_once "lib/session.php";
require_once "lib/render.php";

require_once "lib/render/login.php";
require_once "lib/render/sites.php";

require_once "Auth/OpenID.php";

/**
 * Handle a standard OpenID server request
 */
function action_default()
{
    $server = getServer();
    return handleResponse($server->getOpenIDResponse('isTrusted'));
}

/**
 * Log out the currently logged in user
 */
function action_logout()
{
    setLoggedInUser(null);
    setRequestInfo(null);
    return authCancel(null);
}

/**
 * Check the input values for a login request
 */
function login_checkInput($input)
{
    $openid_url = false;
    $errors = array();

    if (!isset($input['openid_url'])) {
        $errors[] = 'Enter an OpenID URL to continue';
    }
    if (!isset($input['password'])) {
        $errors[] = 'Enter a password to continue';
    }
    if (count($errors) == 0) {
        $openid_url = $input['openid_url'];
        $openid_url = Auth_OpenID::normalizeUrl($openid_url);
        $password = $input['password'];
        if (!checkLogin($openid_url, $password)) {
            $errors[] = 'The entered password does not match the ' .
                'entered identity URL.';
        }
    }
    return array($errors, $openid_url);
}

/**
 * Log in a user and potentially continue the requested identity approval
 */
function action_login()
{
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
    case 'GET':
        return login_render();
    case 'POST':
        $info = getRequestInfo();
        $fields = $_POST;
        if (isset($fields['cancel'])) {
            return authCancel($info);
        }

        list ($errors, $openid_url) = login_checkInput($fields);
        if (count($errors) || !$openid_url) {
            $needed = $info ? $info->getIdentityURL() : false;
            return login_render($errors, @$fields['openid_url'], $needed);
        } else {
            setLoggedInUser($openid_url);
            return doAuth($info);
        }
    default:
        return login_render(array('Unsupported HTTP method: $method'));
    }
}

/**
 * Ask the user whether he wants to trust this site
 */
function action_trust()
{
    $info = getRequestInfo();
    $trusted = isset($_POST['trust']);
    if ($info && isset($_POST['remember'])) {
        $sites = getSessionSites();
        $sites[$info->getTrustRoot()] = $trusted;
        setSessionSites($sites);
    }
    return doAuth($info, $trusted, true);
}

function action_sites()
{
    $sites = getSessionSites();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['forget'])) {
            $sites = null;
            setSessionSites($sites);
        } elseif (isset($_POST['remove'])) {
            foreach ($_POST as $k => $v) {
                if (preg_match('/^site[0-9]+$/', $k) && isset($sites[$v])) {
                    unset($sites[$v]);
                }
            }
            setSessionSites($sites);
        }
    }
    return sites_render($sites);
}

?>