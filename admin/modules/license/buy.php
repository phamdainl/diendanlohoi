<?php

$smarty = \CODOF\Smarty\Single::get_instance();

function getCodoforumBackendBaseUrl()
{
    if (MODE === \Constants::MODE_PRODUCTION) {
        return "https://backend.codoforum.com";
    } else {
        return "http://192.168.0.80:9089";
    }
}
//if(checkoutSessionId){
    //cf backend -> session id -> create
    //redirect to license page
//}

function getSuccessUrl() {
    $successUrl = base64_encode(A_RURI . '?page=license/buy&action=ACTION_NAME&checkoutSessionId={CHECKOUT_SESSION_ID}');
    $licenseId = \CODOF\Util::get_opt("CF_LICENSE_KEY");
    list($key, $secret) = explode("$$", $licenseId);
    $hash = sha1($successUrl . $secret);
    return $successUrl . "$$" . $hash;
}

function getAuthToken() {
    $licenseId = \CODOF\Util::get_opt("CF_LICENSE_KEY");
    list($key, $secret) = explode("$$", $licenseId);
    $hash = sha1($key . $secret);
    return $key . "$$" . $hash;
}

$smarty->assign('token', \CODOF\Access\CSRF::get_token());
$smarty->assign('baseUrl', getCodoforumBackendBaseUrl());

$smarty->assign('callbackUrlToken', getSuccessUrl());
$smarty->assign('authToken', getAuthToken());
$content = $smarty->fetch('license/buy.tpl');