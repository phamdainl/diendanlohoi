<?php

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '5.1']);


if (!\CODOF\Util::optionExists("default_timezone")) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            "option_name" => "default_timezone",
            "option_value" => "Europe/London"
        )
    ));
}