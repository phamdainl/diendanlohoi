<?php

/*
 * @CODOLICENSE
 */
$smarty = \CODOF\Smarty\Single::get_instance();

$db = \DB::getPDO();

$flash = array();

if (isset($_POST['await_approval_subject']) && CODOF\Access\CSRF::valid($_POST['CSRF_token'])) {

    $cfgs = array();
    $flash['flash'] = true;


    foreach ($_POST as $key => $value) {
        $query = "UPDATE " . PREFIX . "codo_config SET option_value=:value WHERE option_name=:key";
        $ps = $db->prepare($query);

        $ps->execute(array(':key' => $key, ':value' => nl2br($value)));
    }

    $flash['message'] = 'Templates saved successfully!';
}


CODOF\Util::get_config($db, true);

$smarty->assign('flash', $flash);
$content = $smarty->fetch('mail/templates.tpl');
