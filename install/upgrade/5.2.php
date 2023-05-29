<?php

use CODOF\Cron\Cron;

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '5.2']);


if (!\CODOF\Util::optionExists("codoforum_server")) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        [
            'option_name' => 'codoforum_server',                    
            'option_value' => 'https://backend.codoforum.com',
        ]
    ));
}

$cron = new Cron();
$cron->set('reload_license_info', 3600 * 24, 'now');