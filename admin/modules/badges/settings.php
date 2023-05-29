<?php

$smarty = \CODOF\Smarty\Single::get_instance();

$smarty->assign('badgeBaseLocation', A_DURI . 'assets/img/badges/');
$smarty->assign('token', \CODOF\Access\CSRF::get_token());
$content = $smarty->fetch('badges/badge_settings.tpl');
