<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.6'
));

// block_main_menu was split into block_main_menu_start and block_main_menu_end
DB::table(PREFIX . 'codo_blocks')->where('region', 'block_main_menu')->update(array(
    'region' => 'block_main_menu_start'
));