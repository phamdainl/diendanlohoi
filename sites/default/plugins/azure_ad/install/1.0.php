<?php

use CODOF\Util;

if (!Util::optionExists('AZURE_CLIENT_ID')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            "option_name" => "AZURE_CLIENT_ID",
            "option_value" => ""
        ),
        array(
            "option_name" => "AZURE_CLIENT_SECRET",
            "option_value" => ""
        ),
        array(
            "option_name" => "AZURE_TENANT_ID",
            "option_value" => ""
        ),
        array(
            "option_name" => "AZURE_CUSTOM_LOGIN",
            "option_value" => "no"
        )
    ));
}
