<?php
/* Copyright 2006 Sxip Identity */
if (!defined('WHOBAR')) die("Please do not access directly");
$openid_to_infocard_attributes = array (
  'http://openid.net/schema/namePerson/first' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname',
  'http://openid.net/schema/namePerson/last' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname',
  'http://openid.net/schema/contact/internet/email' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress',
  'http://openid.net/schema/contact/address/street' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/streetaddress',
  'http://openid.net/schema/contact/address/locality' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/locality',
  'http://openid.net/schema/contact/address/stateOrProvince' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/stateorprovince',
  'http://openid.net/schema/contact/address/postalCode' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/postalcode',
  'http://openid.net/schema/contact/address/country' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/country',
  'http://openid.net/schema/contact/phone/home' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/homephone',
  'http://openid.net/schema/contact/phone/business' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/otherphone',
  'http://openid.net/schema/contact/phone/cell' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/mobilephone',
  'http://openid.net/schema/person/dateOfBirth' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/dateofbirth',
  'http://openid.net/schema/person/gender' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/gender',
  'http://openid.net/schema/person/guid' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/privatepersonalidentifier',
);

function whobar_get_attributes_from_request($post) {
    $ns = "ax";
    $attrs = array();
    foreach ($post as $k => $v) {
        if ($v == "http://openid.net/srv/ax/1.0") {
            $ns = preg_replace("#^openid[._]ns[._]#", "", $k);
        }
    }
    foreach ($post as $k => $v) {
        if (preg_match("#^openid[._]".$ns."[._]fetch[._]#", $k)) {
            $attrs[preg_replace("#^openid[._]".$ns."[._]fetch[._]#", "", $k)] = $v;
        }
    }
    return $attrs;
}

function whobar_map_attributes_for_form($attributes, $format) {
    global $openid_to_infocard_attributes;
    $out = array();
    foreach ($attributes as $k => $v) {
        switch($format) {
            case 'infocard';
                if(!empty($openid_to_infocard_attributes[$v]))
                    $out[$k] = $openid_to_infocard_attributes[$v];
            break;
            default;
                $out[$k] = $attributes[$k];
            break;
        }
    }
    return $out;
}

function whobar_map_attributes_for_site($attributes, $format, $requested = null) {
    global $openid_to_infocard_attributes;
    if ($format == 'openid')
        return $attributes;
    if (!isset($requested))
        die("Missing requested attributes");
    $out = array();
    foreach ($requested as $k => $v) {
        switch($format) {
            case 'infocard';
                $out[$k] = $attributes[preg_replace('#^http://schemas.xmlsoap.org/ws/2005/05/identity/claims/#','',$openid_to_infocard_attributes[$v])];
            break;
            default;
                $out[$k] = $attributes[$k];
            break;
        }
    }
    return $out;
}

function whobar_get_passthru_from_request($request) {
    $passthru = array();
    foreach ($request as $k => $v)
        if (!preg_match("#^openid[_.]#i",$k))
            $passthru[$k] = $v;
    return $passthru;
}

?>
