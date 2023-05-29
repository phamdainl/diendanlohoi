<?php


DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '5.2.1']);

