<?php 
/* Copyright 2006 Sxip Identity */
if (!defined('WHOBAR')) die("Please do not access directly");

function whobar_get_config($name) {
    global $config;
    return isset($config[$name]) ? $config[$name] : null;
}

function whobar_get_script($query='') {
    return whobar_get_config('script_url') . (strpos(whobar_get_config('script_url'), "?") !== false ? "&" : "?") . $query;
}

function whobar_get_session_request() {
    if (isset($_SESSION) and isset($_SESSION['whobar_request'])) {
        return $_SESSION['whobar_request'];
    }
    else {
        return array();
    }
}

function whobar_set_session_request($request) {
    $_SESSION['whobar_request'] = $request;
}

require_once "Services/Yadis/PlainHTTPFetcher.php";

function whobar_proxy_to_site ($postvars) {
    $postvars = array_merge($postvars, whobar_get_passthru_from_request(whobar_get_session_request()));
    $postvars['whobar_secret'] = whobar_get_config('secret');

    $headers = array();
    $allow_headers = array('HTTP_COOKIE', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'HTTP_AUTHORIZATION', 
    'HTTP_IF_MODIFIED_SINCE', 'HTTP_ACCEPT', 'HTTP_ACCEPT_CHARSET', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_IF_MATCH',
    'HTTP_IF_NONE_MATCH', 'HTTP_IF_UNMODIFIED_SINCE', 'HTTP_IF_RANGE');
    foreach ($_SERVER as $k => $v) {
        if (preg_match("/^HTTP_/", $k) and in_array($k, $allow_headers)) {
            $k = preg_replace("/^HTTP_/", "", $k);
            $k = str_replace("_", "-", $k);
            array_push($headers, "$k: $v");
        }
    }

    $ua = new Services_Yadis_PlainHTTPFetcher();
    error_log("posting to whobar handler");
    session_write_close(); // give up the session lock, in case the whobar handler wants it. (otherwise it will block)
    $response = $ua->post(whobar_get_config('handler_url'), http_build_query($postvars), $headers);
    error_log("returned from whobar handler");
    $block_headers = array('connection', 'keep-alive');
    foreach ($response->headers as $h) {
        if (preg_match("/:/", $h)) {
            list($name, $value) = explode(": ", $h, 2);
            if (!in_array(strtolower($name), $block_headers)) {
                header($h);
            }
        }
        else {
            header($h);
        }
    }

    error_log(print_r($response->headers,1));
    echo $response->body;
}
?>
