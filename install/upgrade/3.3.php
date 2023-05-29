<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.3'));

$permission = new CODOF\Permission\Permission();
$permission->add('make sticky', 'forum', array(
    ROLE_MODERATOR, ROLE_ADMIN
        ), true);
$permission->add('edit profile', 'general');


$cron = new CODOF\Cron\Cron();
$cron->set('mail_notify_send', 1800, 'now');

\DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'password_reset_message')
        ->update(array('option_value' => "Hi,\r\n\r\nA request has been made to reset your account password. \r\n\r\To reset your password, please follow the below link:\n[user:link]\r\n\rPassword reset token: [user:token]\r\n\r\nRegards,\r\n[option:site_title] team\r\n"));

\DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'password_reset_subject')
        ->update(array('option_value' => 'Your [option:site_title] password reset request'));


if (!function_exists('optionExists')) {

    function optionExists($option) {

        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}
if (!optionExists('reg_req_admin')) {
    \DB::table(PREFIX . 'codo_config')
            ->insert(array('option_name' => 'reg_req_admin', 'option_value' => 'no'));
}
