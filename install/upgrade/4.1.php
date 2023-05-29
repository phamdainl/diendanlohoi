<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.1'
));

DB::table(PREFIX . 'codo_blocks')->where('theme', 'blue')->update(array(
    'theme' => '2k18'
));

