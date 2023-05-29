<?php

use CODOF\Util;

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update([ 'option_value' => '4.8.8']);

