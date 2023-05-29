<?php

use CODOF\Util;

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update([ 'option_value' => '4.9']);

Util::set_opt('forum_type', 'modern');