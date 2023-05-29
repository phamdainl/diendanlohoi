<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.1.1'
));

DB::table(PREFIX . 'codo_config')->where('option_name', 'theme')->update(array(
    'option_value' => '2k18'
));

