<?php

$smarty = \CODOF\Smarty\Single::get_instance();
$db = \DB::getPDO();

function getCodoforumBackendBaseUrl()
{
    return CODOF\Util::get_opt('codoforum_server');
}


if(isset($_POST['clientIdInput'])){
    $licenseKey = trim($_POST['clientIdInput']);
    if((new CODOF\Service\LicenseService())->reloadLicenseInfoForKey($licenseKey)){
        CODOF\Util::set_opt('CF_LICENSE_KEY',$licenseKey);
                $smarty->assign('flash', [
            'flash'=> true,
            'message' => 'The token '.$licenseKey.' has been linked.'
        ]);
    }else{
     
        $smarty->assign('flash', [
            'flash'=> true,
            'warning'=> true,
            'message' => 'The token '.$licenseKey.' could not be verified.'
        ]);
    }
}

CODOF\Util::get_config($db, true);

$smarty->assign('token', \CODOF\Access\CSRF::get_token());
$smarty->assign('baseApiUrl', getCodoforumBackendBaseUrl() . "/api/v1");
$smarty->assign('baseUrl', getCodoforumBackendBaseUrl());
$smarty->assign('clientId', CODOF\Util::getOption('CF_LICENSE_KEY',null));
$content = $smarty->fetch('license/index.tpl');

